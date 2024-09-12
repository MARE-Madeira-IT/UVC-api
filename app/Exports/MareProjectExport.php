<?php

namespace App\Exports;

use App\Exports\Sheets\MareBenthicSheet;
use App\Exports\Sheets\MareDepthSheet;
use App\Exports\Sheets\MareFunctionSheet;
use App\Exports\Sheets\MareIndicatorSheet;
use App\Exports\Sheets\MareMotileSheet;
use App\Exports\Sheets\MareSiteSheet;
use App\Exports\Sheets\MareSurveySheet;
use App\Exports\Sheets\MareTaxaSheet;
use App\Models\MareBenthic;
use App\Models\MareMotile;
use App\Models\MareReport;
use App\Models\MareReportMotile;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MareProjectExport implements WithMultipleSheets
{
  use Exportable;

  private $project;

  public function __construct($project)
  {
    $this->project = $project;
  }

  public function sheets(): array
  {
    $project = $this->project;
    $motiles = MareMotile::whereHas('mareReportMotile', function ($query) use ($project) {
      return $query->whereHas('report', function ($query) use ($project) {
        return $query->where('project_id', $project->id);
      })->orderBy('type', 'asc');
    })->get();

    $benthics = MareBenthic::whereHas('report', function ($query) use ($project) {
      return $query->where('project_id', $project->id);
    })->get();



    return [
      new MareSiteSheet($project->localities),
      new MareIndicatorSheet($project->indicators),
      new MareDepthSheet($project->depths),
      new MareFunctionSheet($project->functions),
      new MareTaxaSheet($project->taxas, $project->indicators),
      new MareSurveySheet($project->reports, $this->project->functions),
      new MareMotileSheet($motiles),
      new MareBenthicSheet($benthics),
    ];
  }
}
