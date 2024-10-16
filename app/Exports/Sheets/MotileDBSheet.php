<?php

namespace App\Exports\Sheets;

use App\Http\QueryFilters\MotileFilters;
use App\Models\Motile;
use App\Models\SurveyProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class MotileDBSheet implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStrictNullComparison
{
  private $surveyProgram, $index = 0, $request;

  public function __construct(SurveyProgram $surveyProgram, $request)
  {
    $this->surveyProgram = $surveyProgram;
    $this->request = $request;
  }

  public function collection()
  {
    $filters = MotileFilters::hydrate($this->request);

    return Motile::filterBy($filters)->whereHas('mareReportMotile', function ($query) {
      $query->whereHas('report', function ($query) {
        return $query->where('survey_program_id', $this->surveyProgram->id);
      });
    })
      ->join('report_motiles', 'motiles.report_motile_id', '=', 'report_motiles.id')
      ->join('reports', 'report_motiles.report_id', '=', 'reports.id')
      ->orderBy('reports.date', 'asc')
      ->orderBy('report_motiles.type', 'asc')
      ->select('motiles.*')->get();
  }

  public function title(): string
  {
    return 'MOTILE_DB';
  }

  public function headings(): array
  {
    return [
      '###',
      'SAMPLE',
      'survey type',
      'taxa category',
      'Genus',
      'species',
      'taxa#',
      'Size category',
      'Size, cm',
      'ntotal',
      'Surveyed Area',
      'Density /100',
      'density/1',
      'a',
      'b',
      'gr/100',
      'gr/1',
      'NOTAS'
    ];
  }

  public function map($motile): array
  {
    return [
      '###' => ++$this->index,
      'SAMPLE' => $motile->mareReportMotile->report->code,
      'survey type' => $motile->mareReportMotile->type,
      'taxa category' => $motile->taxa->category->name,
      'Genus' => $motile->taxa->genus,
      'species' => $motile->taxa->species,
      'taxa#' => $motile->taxa->name,
      'Size category' => $motile?->sizeCategory?->name,
      'Size, cm' => $motile->size,
      'ntotal' => $motile->ntotal,
      'Surveyed Area' => $motile->mareReportMotile->report->surveyed_area,
      'Density /100' => $motile["density/1"] / 100,
      'density/1' => $motile["density/1"],
      'a' => $motile->taxa->indicators()->where('indicators.name', 'a')->first()?->pivot?->name,
      'b' => $motile->taxa->indicators()->where('indicators.name', 'b')->first()?->pivot?->name,
      'gr/100' => $motile["biomass/1"] / 100,
      'gr/1' => $motile["density/1"],
      'NOTAS' => $motile->notes,
    ];
  }
}
