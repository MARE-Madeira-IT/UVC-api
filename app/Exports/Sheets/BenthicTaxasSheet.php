<?php

namespace App\Exports\Sheets;

use App\Models\SurveyProgram;
use App\Models\Taxa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class BenthicTaxasSheet implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStrictNullComparison
{
  private $surveyProgram;

  public function __construct(SurveyProgram $surveyProgram)
  {
    $this->surveyProgram = $surveyProgram;
  }

  public function collection()
  {
    return Taxa::whereHas('benthics', function ($query) {
      return $query->whereHas('report', function ($query) {
        return $query->where('survey_program_id', $this->surveyProgram->id);
      });
    })->orderBy('name', 'asc')
      ->get();
  }

  public function title(): string
  {
    return 'BENTHIC_TAXAS';
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

  public function map($benthic): array
  {
    $indicators = $this->surveyProgram->indicators;

    $indicatorCols = array();

    foreach ($indicators as $value) {
      array_push($indicatorCols, $benthic->indicators()->where('indicators.name', $value->name)->first()?->pivot?->name);
    }

    return [
      'taxa#' => $benthic->name,
      'Species' => $benthic->species,
      'category' => $benthic->category->name,
      'Genus' => $benthic->genus,
      ...$indicatorCols,
    ];
  }
}
