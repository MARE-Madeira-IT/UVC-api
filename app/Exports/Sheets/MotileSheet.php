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

class MotileSheet implements FromCollection, WithTitle, WithColumnWidths, WithStyles, WithMapping, WithHeadings
{
  private $motiles;

  public function __construct($motiles)
  {
    $this->motiles = $motiles;
  }

  public function title(): string
  {
    return 'Motiles';
  }

  public function collection()
  {
    return $this->motiles;
  }

  public function map($motile): array
  {
    return [
      $motile->mareReportMotile->report->code,
      $motile->mareReportMotile->type,
      $motile->taxa->name,
      $motile->sizeCategory->name ?? "",
      $motile->size ?? "",
      $motile->ntotal,
      $motile->notes,
    ];
  }

  public function headings(): array
  {
    return [
      "Code",
      "Type",
      "Taxa",
      "Size category",
      "Size(cm)",
      "Ntotal",
      "Notes",
    ];
  }

  public function columnWidths(): array
  {
    return [
      'A' => 25,
      'B' => 20,
      'C' => 25,
      'D' => 15,
      'E' => 10,
      'F' => 8,
      'G' => 100,
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      "A1:G1" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],
    ];
  }
}
