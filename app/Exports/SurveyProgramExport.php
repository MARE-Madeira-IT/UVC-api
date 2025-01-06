<?php

namespace App\Exports;

use App\Exports\Sheets\BenthicDBSheet;
use App\Exports\Sheets\BenthicTaxasSheet;
use App\Exports\Sheets\DiveSiteMetadataSheet;
use App\Exports\Sheets\MotileDBSheet;
use App\Exports\Sheets\MotileTaxasSheet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Throwable;

class SurveyProgramExport implements WithMultipleSheets, ShouldQueue, WithEvents
{
  use Exportable;

  protected $export, $request;

  public function __construct($export, $request)
  {
    $this->export = $export;
    $this->request = $request;
  }

  public function sheets(): array
  {
    return [
      new DiveSiteMetadataSheet($this->export->surveyProgram, $this->request),
      new BenthicDBSheet($this->export->surveyProgram, $this->request),
      new BenthicTaxasSheet($this->export->surveyProgram, $this->request),
      new MotileDBSheet($this->export->surveyProgram, $this->request),
      new MotileTaxasSheet($this->export->surveyProgram, $this->request),
    ];
  }

  public function registerEvents(): array
  {
    return [
      BeforeExport::class => function (BeforeExport $event) {
        $this->export->update([
          'state' => 'generating'
        ]);
      },
    ];
  }

  public function failed(Throwable $exception): void
  {
    $this->export->update([
      'state' => 'failed'
    ]);
  }
}
