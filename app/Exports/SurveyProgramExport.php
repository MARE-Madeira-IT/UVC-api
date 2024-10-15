<?php

namespace App\Exports;

use App\Exports\Sheets\BenthicDBSheet;
use App\Exports\Sheets\BenthicTaxasSheet;
use App\Exports\Sheets\DiveSiteMetadataSheet;
use App\Exports\Sheets\MotileDBSheet;
use App\Exports\Sheets\MotileTaxasSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SurveyProgramExport implements WithMultipleSheets
{
  use Exportable;

  protected $surveyProgram, $request;

  public function __construct($surveyProgram, $request)
  {
    $this->surveyProgram = $surveyProgram;
    $this->request = $request;
  }

  public function sheets(): array
  {
    return [
      new DiveSiteMetadataSheet($this->surveyProgram, $this->request),
      new BenthicDBSheet($this->surveyProgram, $this->request),
      new BenthicTaxasSheet($this->surveyProgram, $this->request),
      new MotileDBSheet($this->surveyProgram, $this->request),
      new MotileTaxasSheet($this->surveyProgram, $this->request),
    ];
  }
}
