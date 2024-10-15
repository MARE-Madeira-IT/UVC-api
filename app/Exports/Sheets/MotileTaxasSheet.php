<?php

namespace App\Exports\Sheets;

use App\Http\QueryFilters\TaxaFilters;
use App\Models\SurveyProgram;
use App\Models\Taxa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class MotileTaxasSheet implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStrictNullComparison
{
  private $surveyProgram, $request;

  public function __construct(SurveyProgram $surveyProgram, $request)
  {
    $this->surveyProgram = $surveyProgram;
    $this->request = $request;
  }

  public function collection()
  {
    $filters = TaxaFilters::hydrate($this->request->query());

    return Taxa::filterBy($filters)->whereHas('motiles', function ($query) {
      return $query->whereHas('mareReportMotile', function ($query) {
        return $query->whereHas('report', function ($query) {
          return $query->where('survey_program_id', $this->surveyProgram->id);
        });
      });
    })->orderBy('name', 'asc')
      ->get();
  }

  public function title(): string
  {
    return 'MOTILE_TAXAS';
  }

  public function headings(): array
  {
    $indicators = $this->surveyProgram->indicators;

    $indicatorsCol = array();

    foreach ($indicators as $value) {
      array_push($indicatorsCol, $value->name);
    }

    return [
      'taxa#',
      'Species',
      'category',
      'Genus',
      ...$indicatorsCol,
    ];
  }

  public function map($motile): array
  {
    $indicators = $this->surveyProgram->indicators;

    $indicatorCols = array();

    foreach ($indicators as $value) {
      array_push($indicatorCols, $motile->indicators()->where('indicators.name', $value->name)->first()?->pivot?->name);
    }

    return [
      'taxa#' => $motile->name,
      'Species' => $motile->species,
      'category' => $motile->category->name,
      'Genus' => $motile->genus,
      ...$indicatorCols,
    ];
  }
}
