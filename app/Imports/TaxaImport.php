<?php

namespace App\Imports;

use App\Helpers\ImportHelper;
use App\Models\Indicator;
use App\Models\SurveyProgram;
use App\Models\Taxa;
use App\Models\TaxaCategory;
use App\Models\TaxaHasIndicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class TaxaImport implements ToCollection, WithValidation, WithHeadingRow, SkipsEmptyRows, OnEachRow
{
    private $surveyProgram, $sheetName;

    function __construct(SurveyProgram $surveyProgram, $sheetName)
    {
        $this->surveyProgram = $surveyProgram;
        $this->sheetName = $sheetName;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        cache()->forever("current_row_{$this->sheetName}_{$this->surveyProgram->id}", $rowIndex);
    }


    public function rules(): array
    {
        return [
            'category' => "required|string|in:Macroinvertebrate,Substrate,Algae,Fish,Litter,Other",
            'taxa' => 'required|string',
            'species' => 'required|string',
            'genus' => 'required|string',
            'phylum' => 'nullable|string'
        ];
    }

    public function customValidationMessages()
    {
        return [
            "category.*" => $this->sheetName . " (:row): The :attribute with value ':input' must be one of the following: Macroinvertebrate, Substrate, Algae, Fish, Litter, Other",
            "species.*" => $this->sheetName . " (:row): The :attribute is required",
            "genus.*" => $this->sheetName . " (:row): The :attribute is required",
            "taxa.*" => $this->sheetName . " (:row): The :attribute is required",
        ];
    }

    public function isEmptyWhen(array $row): bool
    {
        return array_key_exists("taxa", $row) ? $row['taxa'] == null : false;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $surveyProgram = $this->surveyProgram;

        $columnNames = array_key_exists(0, $rows->toArray()) ? array_keys($rows[0]->toArray()) : [];

        $indicatorsStart = ImportHelper::findColNumber($columnNames, 'indicators') + 1;  //external
        $indicators = array_filter(array_slice($columnNames, $indicatorsStart), function ($el) {
            return is_string($el);
        });

        $indicatorsCol = array();

        foreach ($indicators as $indicatorName) {
            if ($indicatorName) {
                Indicator::updateOrCreate([
                    "survey_program_id" => $surveyProgram->id,
                    "name" => $indicatorName,
                ], [
                    "survey_program_id" => $surveyProgram->id,
                    "type" => 'text',
                    "name" => $indicatorName,
                ]);

                array_push($indicatorsCol, $indicatorName);
            }
        }

        foreach ($rows->toArray() as $row) {
            if ($row["species"] == null) {
                continue;
            }

            $taxaCategory = TaxaCategory::updateOrCreate([
                'survey_program_id' => $surveyProgram->id,
                'name' => array_key_exists("category", $row) ? $row["category"] : null
            ]);

            $taxa = Taxa::updateOrCreate([
                'survey_program_id' => $surveyProgram->id,
                'name' => array_key_exists("taxa", $row) ? $row["taxa"] : null,
            ], [
                'survey_program_id' => $surveyProgram->id,
                'name' => array_key_exists("taxa", $row) ?  $row["taxa"] : null,
                'species' => array_key_exists("species", $row) ?  $row["species"] : null,
                'genus' => array_key_exists("genus", $row) ?  $row["genus"] : null,
                'phylum' => array_key_exists("phylum", $row) ?  $row["phylum"] : null,
                'category_id' => $taxaCategory->id,
                'photo_url' => null,
                'validated' => true,
            ]);
            if (count($indicatorsCol) > 0) {
                foreach ($indicatorsCol as $indicatorCol) {
                    if ($indicatorCol && $row[$indicatorCol]) {
                        TaxaHasIndicator::create(
                            [
                                "taxa_id" => $taxa->id,
                                "indicator_id" => Indicator::where('survey_program_id', $surveyProgram->id)
                                    ->where('name', $indicatorCol)->first()->id,
                                "name" => $row[$indicatorCol],
                            ]
                        );
                    }
                }
            }
        }
    }
}
