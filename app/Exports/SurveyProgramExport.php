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

  protected $surveyProgram;

  public function __construct($surveyProgram)
  {
    $this->surveyProgram = $surveyProgram;
  }

  public function sheets(): array
  {
    return [
      new DiveSiteMetadataSheet($this->surveyProgram),
      new BenthicDBSheet($this->surveyProgram),
      new BenthicTaxasSheet($this->surveyProgram),
      new MotileDBSheet($this->surveyProgram),
      new MotileTaxasSheet($this->surveyProgram),
    ];
  }
}
