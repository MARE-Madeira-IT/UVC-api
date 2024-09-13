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

class DepthSheet implements FromCollection, WithTitle, WithColumnWidths, WithStyles, WithMapping, WithHeadings
{
  private $depths;

  public function __construct($depths)
  {
    $this->depths = $depths;
  }

  public function title(): string
  {
    return 'Depths';
  }

  public function collection()
  {
    return $this->depths;
  }

  public function map($depth): array
  {
    return [
      $depth->name,
    ];
  }

  public function headings(): array
  {
    return [
      'Name',
    ];
  }

  public function columnWidths(): array
  {
    return [
      'A' => 50,
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      "A1" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],
    ];
  }
}
