<?php

namespace App\Exports\Sheets;

use App\Http\QueryFilters\BenthicsFilters;
use App\Models\Benthic;
use App\Models\SurveyProgram;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class BenthicDBSheet implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStrictNullComparison
{
  private $surveyProgram, $request, $index = 0;

  public function __construct(SurveyProgram $surveyProgram, $request)
  {
    $this->surveyProgram = $surveyProgram;
    $this->request = $request;
  }

  public function collection()
  {
    $filters = BenthicsFilters::hydrate($this->request);

    return Benthic::filterBy($filters)->whereHas('report', function ($query) {
      return $query->where('survey_program_id', $this->surveyProgram->id);
    })
      ->join('reports', 'benthics.report_id', '=', 'reports.id')
      ->orderBy('reports.date', 'asc')
      ->orderBy('benthics.p##', 'asc')
      ->select('benthics.*')->get();
  }

  public function title(): string
  {
    return 'BENTHIC_DB';
  }

  public function headings(): array
  {
    return [
      '###',
      'SAMPLE#',
      'P##',
      'TAXA CAT',
      'SUBSTRATE',
      'NOTE',
      'taxa'
    ];
  }

  public function map($benthic): array
  {
    return [
      '###' => ++$this->index,
      'SAMPLE#' => $benthic->report->getCode(),
      'P##' => $benthic["p##"],
      'TAXA CAT' => $benthic->taxa->category->name,
      'SUBSTRATE' => $benthic->substrate->name,
      'NOTE' => $benthic->notes,
      'taxa' => $benthic->taxa->name,
    ];
  }
}
