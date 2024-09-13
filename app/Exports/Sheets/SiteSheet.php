<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiteSheet implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
  private $localities;

  public function __construct($localities)
  {
    $this->localities = $localities;
  }

  public function title(): string
  {
    return 'Sites';
  }

  public function array(): array
  {
    $data = [
      ["Localities", "", "Sites"],
      ["Locality", "Locality Code", "Name", "Site Code"]
    ];

    foreach ($this->localities as $locality) {
      foreach ($locality->sites as $site) {
        $data[] = [
          "{$locality->name}",
          "{$locality->code}",
          "{$site->name}",
          "{$site->code}",
        ];
      }
    }

    return $data;
  }

  public function columnWidths(): array
  {
    return [
      'A' => 20,
      'B' => 14,
      'C' => 20,
      'D' => 10,
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      "A1:D2" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],
    ];
  }
}
