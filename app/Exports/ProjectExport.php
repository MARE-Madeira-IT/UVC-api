<?php

namespace App\Exports;

use App\Exports\Sheets\BenthicSheet;
use App\Exports\Sheets\DepthSheet;
use App\Exports\Sheets\ProjectFunctionSheet;
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

class ProjectExport implements WithMultipleSheets
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
    $motiles = Motile::whereHas('mareReportMotile', function ($query) use ($project) {
      return $query->whereHas('report', function ($query) use ($project) {
        return $query->where('project_id', $project->id);
      })->orderBy('type', 'asc');
    })->get();

    $benthics = Benthic::whereHas('report', function ($query) use ($project) {
      return $query->where('project_id', $project->id);
    })->get();



    return [
      new SiteSheet($project->localities),
      new IndicatorSheet($project->indicators),
      new DepthSheet($project->depths),
      new ProjectFunctionSheet($project->functions),
      new TaxaSheet($project->taxas, $project->indicators),
      new SurveySheet($project->reports, $this->project->functions),
      new MotileSheet($motiles),
      new BenthicSheet($benthics),
    ];
  }
}
