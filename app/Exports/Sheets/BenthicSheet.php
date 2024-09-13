<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BenthicSheet implements FromCollection, WithTitle, WithColumnWidths, WithStyles, WithMapping, WithHeadings
{
  private $benthics;

  public function __construct($benthics)
  {
    $this->benthics = $benthics;
  }

  public function title(): string
  {
    return 'Benthics';
  }

  public function collection()
  {
    return $this->benthics;
  }

  public function map($benthic): array
  {
    return [
      $benthic->report->code,
      $benthic["p##"],
      $benthic->substrate->name,
      $benthic->taxa->name,
      $benthic->notes,
    ];
  }

  public function headings(): array
  {
    return [
      "Code",
      "P##",
      "Substrate",
      "Taxa",
      "Notes",
    ];
  }

  public function columnWidths(): array
  {
    return [
      'A' => 25,
      'B' => 5,
      'C' => 10,
      'D' => 25,
      'E' => 50,
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      "A1:E1" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],
    ];
  }
}
