<?php

namespace App\Exports\Sheets;

use App\Http\QueryFilters\ReportFilters;
use App\Models\Report;
use App\Models\SurveyProgram;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class DiveSiteMetadataSheet implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStrictNullComparison
{
  private $surveyProgram, $request, $index = 0;

  public function __construct(SurveyProgram $surveyProgram, $request)
  {
    $this->surveyProgram = $surveyProgram;
    $this->request = $request;
  }

  public function collection()
  {
    $filters = ReportFilters::hydrate($this->request);

    return $this->surveyProgram->reports()->filterBy($filters)->orderBy('date', 'asc')->get();
  }

  public function title(): string
  {
    return 'DIVE_SITE_METADATA';
  }

  public function headings(): array
  {
    $functions = $this->surveyProgram->functions;

    $functionCols = array();

    foreach ($functions as $value) {
      array_push($functionCols, $value->name);
    }

    return [
      "###",
      "SAMPLE#",
      "Date",
      "Locality",
      "Locality Code",
      "Site",
      "Site Code",
      "Daily_dive",
      "Transect",
      "Depth category",
      "Depth#",
      "Time#",
      "Replica",
      "sample",
      "Site_Depth",
      "Latitude",
      "Longitude",
      "Heading",
      "Heading direction",
      "SITE AREA",
      "DISTANCE",
      "#Dive Team",
      ...$functionCols
    ];
  }

  public function map($report): array
  {
    $functionCols = $report->functions->pluck('pivot.user')->toArray();

    return [
      "###" => ++$this->index,
      "SAMPLE#" => $report->code,
      'date' => Carbon::parse($report->date)->format('Ymd'),
      'Locality' => $report->site->locality->name,
      'Locality Code' => $report->site->locality->code,
      'Site' => $report->site->name,
      'Site Code' => $report->site->code,
      'Daily_dive' => $report->daily_dive,
      'Transect' => $report->transect,
      'Depth category' => $report->depth->name,
      'Depth#' => $report->depth->code,
      'Time#' => $report->time,
      'Replica' => $report->replica,
      'sample' => $report->code,
      'Site_Depth' => $report->site->locality->code . "_" . $report->site->code . "_T" . $report->time . "_D" . $report->depth->code,
      'Latitude' => $report->latitude,
      'Longitude' => $report->longitude,
      'Heading' => $report->heading,
      'Heading direction' => $report->heading_direction,
      'SITE AREA' => $report->site_area,
      'DISTANCE' => $report->distance,
      '#Dive Team' => null,
      ...$functionCols
    ];
  }
}
