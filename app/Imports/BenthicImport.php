<?php

namespace App\Imports;

use App\Models\Benthic;
use App\Models\Report;
use App\Models\Substrate;
use App\Models\SurveyProgram;
use App\Models\Taxa;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;

class BenthicImport implements ToCollection, WithValidation, WithHeadingRow, SkipsEmptyRows, OnEachRow
{
    private $surveyProgram, $sheetName;

    function __construct(SurveyProgram $surveyProgram, $sheetName)
    {
        $this->surveyProgram = $surveyProgram;
        $this->sheetName = $sheetName;
    }

    public function isEmptyWhen(array $row): bool
    {
        return array_key_exists("sample", $row) ? $row['sample'] == null : false;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        cache()->forever("current_row_{$this->sheetName}_{$this->surveyProgram->id}", $rowIndex);
    }

    public function rules(): array
    {
        return [
            'sample' => ["required", Rule::exists("reports", "code")->where(function ($q) {
                return $q->where('survey_program_id', $this->surveyProgram->id);
            })],
            'p' => "required|integer|between:1,100",
            'taxa' => ["required", Rule::exists("taxas", "name")->where(function ($q) {
                return $q->where('survey_program_id', $this->surveyProgram->id);
            })],
            'substrate' => "required|exists:substrates,name",
        ];
    }

    public function customValidationMessages()
    {
        return [
            "sample.exists" => $this->sheetName . " (:row): The :attribute with value ':input' doesn't exist on the 'DIVE_SITE_METADATA' sheet",
            "sample.required" => $this->sheetName . " (:row): The :attribute is required",
            "p.*" => $this->sheetName . " (:row): The :attribute with value ':input' must be an integer between 1 and 100 (inclusive)",
            "taxa.exists" => $this->sheetName . " (:row): The :attribute with value ':input' doesn't exist on the 'BENTHIC_TAXAS' sheet",
            "taxa.required" => $this->sheetName . " (:row): The :attribute is required",
            "substrate.exists" => $this->sheetName . " (:row): The :attribute with value ':input' must be one of the following: " . implode(', ', Substrate::all()->pluck("name")->toArray()),
            "substrate.required" => $this->sheetName . " (:row): The :attribute is required",
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $surveyProgram = $this->surveyProgram;

        $benthicData = [];

        foreach ($rows->toArray() as $i => $row) {
            if ($row["sample"] == null) {
                continue;
            }

            $substrate = Substrate::whereRaw('LOWER(name) = (?)', [strtolower($row["substrate"])])
                ->first();


            $taxaName = $row["taxa"];
            $taxa = Taxa::where('survey_program_id', $surveyProgram->id)
                ->where('name', $taxaName)
                ->first();

            $sampleName = $row["sample"];
            $report = Report::where("code", $sampleName)
                ->where('survey_program_id', $surveyProgram->id)
                ->first();

            $benthicData[] = [
                "report_id" => $report->id,
                "substrate_id" => $substrate->id,
                "notes" => array_key_exists("notes", $row) ? $row["notes"] : null,
                "taxa_id" => $taxa->id,
                "p##" => $row["p"],
            ];
        }

        foreach (array_chunk($benthicData, 1000) as $t) {
            Benthic::insert($t);
        }
    }
}
