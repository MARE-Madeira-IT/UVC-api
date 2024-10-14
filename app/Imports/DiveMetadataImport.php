<?php

namespace App\Imports;

use App\Helpers\ImportHelper;
use App\Models\Depth;
use App\Models\Locality;
use App\Models\Report;
use App\Models\ReportHasFunction;
use App\Models\Site;
use App\Models\SurveyProgram;
use App\Models\SurveyProgramFunction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class DiveMetadataImport implements ToCollection, WithValidation, WithHeadingRow, SkipsEmptyRows, OnEachRow
{
    private $surveyProgram, $sheetName;

    function __construct(SurveyProgram $surveyProgram, $sheetName)
    {
        $this->surveyProgram = $surveyProgram;
        $this->sheetName = $sheetName;
    }

    public function isEmptyWhen(array $row): bool
    {
        return array_key_exists("date", $row) ? $row['date'] == null : false;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        cache()->forever("current_row_{$this->sheetName}_{$this->surveyProgram->id}", $rowIndex);
    }

    public function prepareForValidation($row, $index)
    {
        $code = $row["locality_code"] . '_' . $row["site_code"] . '_Time' . $row["time"] . '_D' . $row["depth"] . '_R' . $row["replica"];

        $data = ["code" => $code, ...$row];

        return $data;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'string',
                'distinct',
            ],
            'date' => 'required|date_format:Ymd',
            'locality' => 'required|string',
            'locality_code' => 'required|string',
            'site' => 'required|string',
            'site_code' => 'required|string',
            'daily_dive' => 'required|integer',
            'transect' => 'required|integer',
            'depth_category' => 'required|string',
            'depth' => 'required|integer',
            'time' => 'required|numeric',
            'replica' => 'required|integer',
            'latitude' => ['nullable', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'longitude' => ['nullable', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,20})?))$/'],
            'heading' => 'nullable|integer',
            'heading_direction' => 'nullable|string',
            'site_area' => 'nullable',
            'distance' => 'nullable|decimal:0,9',
        ];
    }

    public function customValidationMessages()
    {
        return [
            "date.*" => $this->sheetName . " (:row): The :attribute must be a date",
            "locality.*" => $this->sheetName . " (:row): The :attribute is required",
            "locality_code.*" => $this->sheetName . " (:row): The :attribute is required",
            "site.*" => $this->sheetName . " (:row): The :attribute is required",
            "site_code.*" => $this->sheetName . " (:row): The :attribute is required",
            "daily_dive.*" => $this->sheetName . " (:row): The :attribute is required",
            "transect.*" => $this->sheetName . " (:row): The :attribute is required",
            "depth_category.*" => $this->sheetName . " (:row): The :attribute is required",
            "depth.*" => $this->sheetName . " (:row): The :attribute is required",
            "time.required" => $this->sheetName . " (:row): The :attribute is required",
            "time.numeric" => $this->sheetName . " (:row): The :attribute with value ':input' must be a number",
            "replica.*" => $this->sheetName . " (:row): The :attribute is required",
            "latitude.*" => $this->sheetName . " (:row): The :attribute is not valid",
            "longitude.*" => $this->sheetName . " (:row): The :attribute is not valid",
            "heading.*" => $this->sheetName . " (:row): The :attribute is not valid",
            "heading_direction.*" => $this->sheetName . " (:row): The :attribute is not valid",
            "distance.*" => $this->sheetName . " (:row): The :attribute with value ':input' must be a number",
            "code.distinct" => $this->sheetName . " (:row): A sample with code ':input' is duplicated in the file",
            "code.unique" => $this->sheetName . " (:row): A sample with code ':input' already exists",
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $surveyProgram = $this->surveyProgram;

        $columnNames = array_key_exists(0, $rows->toArray()) ? array_keys($rows[0]->toArray()) : [];

        $functionsStart = ImportHelper::findColNumber($columnNames, 'functions') + 1;  //external
        $functions = array_filter(array_slice($columnNames, $functionsStart), function ($el) {
            return is_string($el);
        });

        $functionsCol = array();

        foreach ($functions as $functionName) {
            SurveyProgramFunction::updateOrCreate([
                "survey_program_id" => $surveyProgram->id,
                "name" => $functionName
            ], [
                "survey_program_id" => $surveyProgram->id,
                "name" => $functionName
            ]);

            array_push($functionsCol, $functionName);
        }


        foreach ($rows->toArray() as $row) {
            if ($row["date"] == null) {
                continue;
            }

            $locality = Locality::updateOrCreate([
                "survey_program_id" => $surveyProgram->id,
                "code" => $row["locality_code"],
            ], [
                "survey_program_id" => $surveyProgram->id,
                "code" => $row["locality_code"],
                "name" => $row["locality"],
            ]);

            $site = Site::updateOrCreate([
                "locality_id" => $locality->id,
                "code" => $row["site_code"],
            ], [
                "locality_id" => $locality->id,
                "code" => $row["site_code"],
                "name" => $row["site"],
                "latitude" => $row["latitude"],
                "longitude" => $row["longitude"],
            ]);

            $depth = Depth::updateOrCreate([
                "survey_program_id" => $surveyProgram->id,
                "name" => $row["depth_category"],
            ], [
                "survey_program_id" => $surveyProgram->id,
                "name" => $row["depth_category"],
                "code" => $row["depth"],
            ]);

            $code = $locality->code . '_' . $site->code . '_Time' . $row["time"] . '_D' . $depth->code . '_R' . $row["replica"];

            $report = Report::create([
                "survey_program_id" => $surveyProgram->id,
                "time" => $row['time'],
                "code" => $code,
                "date" => Carbon::createFromFormat("Ymd", $row['date']),
                'transect' => $row['transect'],
                'daily_dive' => $row['daily_dive'],
                'replica' => $row['replica'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'heading' => $row['heading'],
                'heading_direction' => $row['heading_direction'],
                'site_area' => $row['site_area'],
                'distance' => $row['distance'],
                'site_id' => $site->id,
                'depth_id' => $depth->id,
                'surveyed_area' => 100 //It is by default 100 and then changed when importing the motile page
            ]);

            if (count($functionsCol) > 0) {
                foreach ($functionsCol as $functionCol) {
                    if ($functionCol && $row[$functionCol]) {
                        ReportHasFunction::create([
                            'function_id' => SurveyProgramFunction::where('survey_program_id', $surveyProgram->id)
                                ->where('name', $functionCol)->first()->id,
                            'report_id' => $report->id,
                            'user' => $row[$functionCol],
                        ]);
                    }
                }
            }
        }
    }
}
