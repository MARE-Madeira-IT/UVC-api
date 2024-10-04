<?php

namespace App\Imports;

use App\Models\Motile;
use App\Models\Report;
use App\Models\ReportMotile;
use App\Models\SizeCategory;
use App\Models\SurveyProgram;
use App\Models\Taxa;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class MotileImport implements ToCollection, WithValidation, WithHeadingRow, SkipsEmptyRows, OnEachRow
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
            'sample' => ["required", Rule::exists("reports", "code")->where(function ($q) {
                return $q->where('survey_program_id', $this->surveyProgram->id);
            })],
            'survey_type' => "required|string|in:fish,macroinvertebrates,cryptic,dom_urchin",
            'taxa' => ["required", Rule::exists("taxas", "name")->where(function ($q) {
                return $q->where('survey_program_id', $this->surveyProgram->id);
            })],
            'size_category' => "nullable|exists:size_categories,name",
            'size' => "nullable|numeric",
            'nTotal' => "nullable|integer",
            'notes' => "nullable|string",
        ];
    }

    public function customValidationMessages()
    {
        return [
            "sample.exists" => $this->sheetName . " (:row): The :attribute with value ':input' doesn't exist on the 'DIVE_SITE_METADATA' sheet",
            "sample.required" => $this->sheetName . " (:row): The :attribute is required",
            "survey_type.*" => $this->sheetName . " (:row): The :attribute with value ':input' must be one of the following: fish, macroinvertebrates, cryptic, dom_urchin",
            "taxa.exists" => $this->sheetName . " (:row): The :attribute with value ':input' doesn't exist on the 'MOTILE_TAXAS' sheet",
            "taxa.required" => $this->sheetName . " (:row): The :attribute is required",
            "size_category.exists" => $this->sheetName . " (:row): The :attribute with value ':input' must be one of the following: " . implode(', ', SizeCategory::all()->pluck("name")->toArray()),
            "size.*" => $this->sheetName . " (:row): The :attribute must be a number",
            "nTotal.*" => $this->sheetName . " (:row): The :attribute must be a number",
        ];
    }

    public function isEmptyWhen(array $row): bool
    {
        return $row['sample'] == null;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $surveyProgram = $this->surveyProgram;

        $motilesData = [];

        foreach ($rows->toArray() as $row) {
            if ($row["sample"] == null) {
                continue;
            }

            $taxaName = $row["taxa"];
            $taxa = Taxa::where('survey_program_id', $surveyProgram->id)
                ->where('name', $taxaName)
                ->first();

            $sampleName = $row["sample"];
            $report = Report::where("code", $sampleName)
                ->where('survey_program_id', $surveyProgram->id)
                ->first();

            $reportMotile = ReportMotile::updateOrCreate([
                "report_id" => $report->id,
                "type" => $row["survey_type"]
            ], [
                "report_id" => $report->id,
                "type" => $row["survey_type"]
            ]);

            $sizeCategoryName = $row["size_category"];
            if ($sizeCategoryName) {
                $sizeCategory = SizeCategory::where("name", $sizeCategoryName)->first();
            }

            $aIndicator = $taxa->indicators()->where('indicators.name', 'a')->first();
            $bIndicator = $taxa->indicators()->where('indicators.name', 'b')->first();

            $biomass = null;
            $density = null;

            if (isset($aIndicator)) {
                $aValue = (float) $aIndicator->pivot->name;
            }

            if (isset($bIndicator)) {
                $bValue = (float) $bIndicator->pivot->name;
            }

            if (isset($aValue) && isset($bValue)) {
                $biomass = $aValue * pow((float) $row["size"], $bValue);
            }

            if (isset($biomass)) {
                $density = (float) $row["ntotal"] / $reportMotile->report->surveyed_area;
            }

            $motilesData[] = [
                "report_motile_id" => $reportMotile->id,
                "taxa_id" => $taxa->id,
                "size_category_id" => $sizeCategoryName ? $sizeCategory->id : null,
                "size" => $row["size"],
                'density/1' => $density,
                'biomass/1' => $biomass,
                "ntotal" => $row["ntotal"],
                "notes" => $row["notes"],
            ];
        }

        foreach (array_chunk($motilesData, 1000) as $t) {
            Motile::insert($t);
        }
    }
}
