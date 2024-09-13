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

class ProjectFunctionSheet implements FromCollection, WithTitle, WithColumnWidths, WithStyles, WithMapping, WithHeadings
{
  private $functions;

  public function __construct($functions)
  {
    $this->functions = $functions;
  }

  public function title(): string
  {
    return 'Functions';
  }

  public function collection()
  {
    return $this->functions;
  }

  public function map($function): array
  {
    return [
      $function->name,
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
