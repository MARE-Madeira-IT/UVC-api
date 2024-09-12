<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MareTaxaSheet implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
  private $taxas;
  private $indicators;

  public function __construct($taxas, $indicators)
  {
    $this->taxas = $taxas;
    $this->indicators = $indicators;
  }

  public function title(): string
  {
    return 'Taxas';
  }

  public function array(): array
  {
    $data = [
      ["", "", "", "", ""],
    ];

    if (count($this->indicators) > 0) {
      $data = [
        ["", "", "", "", "", "Indicators"],
      ];
    }

    $titles = ["Category", "Name", "Genus", "Species", "Phylum"];

    foreach ($this->indicators as $indicator) {
      $titles[] = $indicator->name;
    }

    $data[] = $titles;

    foreach ($this->taxas as $taxa) {
      $row = [
        "{$taxa->category->name}",
        "{$taxa->name}",
        "{$taxa->genus}",
        "{$taxa->species}",
        "{$taxa->phylum}",
      ];

      foreach ($this->indicators as $indicator) {
        $taxaIndicator = $taxa->indicators()->find($indicator->id);

        if ($taxaIndicator) {
          $row[] = "{$taxaIndicator->pivot->name}";
        } else {
          $row[] = "";
        }
      }

      $data[] = $row;
    }

    return $data;
  }

  public function columnWidths(): array
  {
    $alphabet = range('F', 'Z');

    $widths = [
      'A' => 10,
      'B' => 30,
      'C' => 20,
      'D' => 20,
      'E' => 20,
    ];

    for ($i = 0; $i < count($this->indicators); $i++) {
      $widths[$alphabet[$i]] = 15;
    }

    return $widths;
  }

  public function styles(Worksheet $sheet)
  {
    $alphabet = range('A', 'Z');
    $indicatorsCount = count($this->indicators);

    $styling =  [
      "A1:" . $alphabet[5 + $indicatorsCount - 1] . "1" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],
      "A2:E2" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],

    ];

    if ($indicatorsCount > 0) {
      $styling["F2:" . $alphabet[5 + $indicatorsCount - 1] . "2"] =
        [
          "font" => ["bold" => true],
          'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['argb' => "cfe2f3"],
          ],
        ];
    }

    return $styling;
  }
}
