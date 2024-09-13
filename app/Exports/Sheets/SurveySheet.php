<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveySheet implements FromArray, WithTitle, WithColumnWidths, WithStyles
{
  private $surveys;
  private $functions;

  public function __construct($surveys, $functions)
  {
    $this->surveys = $surveys;
    $this->functions = $functions;
  }

  public function title(): string
  {
    return 'Surveys';
  }

  public function array(): array
  {
    $firstRow = [];

    for ($i = 0; $i < 15; $i++) {
      $firstRow[] = "";
    }

    if (count($this->functions) > 0) {
      $firstRow[] = "Functions";
    }

    $data[] = $firstRow;

    $titles = [
      "Code",
      "Latitude",
      "Longitude",
      "Date",
      "Site",
      "Depth",
      "Heading",
      "Heading direction",
      "Site area",
      "Distance",
      "Daily dive#",
      "Transect#",
      "Time#",
      "Replica#",
      "Surveyed area",
    ];

    foreach ($this->functions as $function) {
      $titles[] = $function->name;
    }

    $data[] = $titles;

    foreach ($this->surveys as $survey) {
      $row = [
        "{$survey->code}",
        "{$survey->latitude}",
        "{$survey->longitude}",
        "{$survey->date}",
        "{$survey->site->code}",
        "{$survey->depth->name}",
        "{$survey->heading}",
        "{$survey->heading_direction}",
        "{$survey->site_area}",
        "{$survey->distance}",
        "{$survey->daily_dive}",
        "{$survey->transect}",
        "{$survey->time}",
        "{$survey->replica}",
        "{$survey->surveyed_area}",
      ];

      foreach ($this->functions as $function) {
        $surveyFunction = $survey->functions()->find($function->id);

        if ($surveyFunction) {
          $row[] = $surveyFunction->pivot->user;
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
    $alphabet = range('B', 'Z');

    $widths = [
      'A' => 30,
    ];

    for ($i = 0; $alphabet[$i] < 'O'; $i++) {
      $widths[$alphabet[$i]] = 15;
    }

    for ($k = 0; $k <= count($this->functions); $k++) {
      $widths[$alphabet[$i + $k]] = 15;
    }


    return $widths;
  }

  public function styles(Worksheet $sheet)
  {
    $alphabet = range('A', 'Z');
    $functionsCount = count($this->functions);

    $styling =  [
      "A1:" . $alphabet[15 + $functionsCount - 1] . "1" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],
      "A2:O2" => [
        "font" => ["bold" => true],
        'fill' => [
          'fillType'   => Fill::FILL_SOLID,
          'startColor' => ['argb' => "9fc5e8"],
        ],
      ],

    ];

    if ($functionsCount > 0) {
      $styling["P2:" . $alphabet[15 + $functionsCount - 1] . "2"] =
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
