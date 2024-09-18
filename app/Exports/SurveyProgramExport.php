<?php

namespace App\Exports;

use App\Exports\Sheets\BenthicSheet;
use App\Exports\Sheets\DepthSheet;
use App\Exports\Sheets\SurveyProgramFunctionSheet;
use App\Exports\Sheets\IndicatorSheet;
use App\Exports\Sheets\MotileSheet;
use App\Exports\Sheets\SiteSheet;
use App\Exports\Sheets\SurveySheet;
use App\Exports\Sheets\TaxaSheet;
use App\Models\Benthic;
use App\Models\Motile;
use App\Models\Report;
use App\Models\ReportMotile;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SurveyProgramExport implements WithMultipleSheets
{
  use Exportable;

  private $surveyProgram;

  public function __construct($surveyProgram)
  {
    $this->surveyProgram = $surveyProgram;
  }

  public function sheets(): array
  {
    $surveyProgram = $this->surveyProgram;
    $motiles = Motile::whereHas('mareReportMotile', function ($query) use ($surveyProgram) {
      return $query->whereHas('report', function ($query) use ($surveyProgram) {
        return $query->where('survey_program_id', $surveyProgram->id);
      })->orderBy('type', 'asc');
    })->get();

    $benthics = Benthic::whereHas('report', function ($query) use ($surveyProgram) {
      return $query->where('survey_program_id', $surveyProgram->id);
    })->get();



    return [
      new SiteSheet($surveyProgram->localities),
      new IndicatorSheet($surveyProgram->indicators),
      new DepthSheet($surveyProgram->depths),
      new SurveyProgramFunctionSheet($surveyProgram->functions),
      new TaxaSheet($surveyProgram->taxas, $surveyProgram->indicators),
      new SurveySheet($surveyProgram->reports, $this->surveyProgram->functions),
      new MotileSheet($motiles),
      new BenthicSheet($benthics),
    ];
  }
}
