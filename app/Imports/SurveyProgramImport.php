<?php

namespace App\Imports;

use App\Models\SurveyProgram;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class SurveyProgramImport implements WithMultipleSheets, WithEvents, ShouldQueue, WithChunkReading, WithBatchInserts
{
    private $surveyProgram, $sheets;

    public function chunkSize(): int
    {
        return 400;
    }

    public function batchSize(): int
    {
        return 400;
    }

    function __construct(SurveyProgram $surveyProgram)
    {
        $this->surveyProgram = $surveyProgram;

        $this->sheets["DIVE_SITE_METADATA"] = new DiveMetadataImport($this->surveyProgram, "DIVE_SITE_METADATA");
        $this->sheets["BENTHIC_TAXAS"] = new TaxaImport($this->surveyProgram, "BENTHIC_TAXAS");
        $this->sheets["MOTILE_TAXAS"] = new TaxaImport($this->surveyProgram, "MOTILE_TAXAS");
        $this->sheets['BENTHIC_DB'] = new BenthicImport($this->surveyProgram, "BENTHIC_DB");
        $this->sheets['MOTILE_DB'] = new MotileImport($this->surveyProgram, "MOTILE_DB");
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $rowsPerSheet = $event->getReader()->getTotalRows();

                if (filled($rowsPerSheet)) {
                    cache()->forever("total_rows_DIVE_SITE_METADATA_{$this->surveyProgram->id}", $rowsPerSheet["DIVE_SITE_METADATA"]);
                    cache()->forever("total_rows_BENTHIC_TAXAS_{$this->surveyProgram->id}", $rowsPerSheet["BENTHIC_TAXAS"]);
                    cache()->forever("total_rows_MOTILE_TAXAS_{$this->surveyProgram->id}", $rowsPerSheet["MOTILE_TAXAS"]);
                    cache()->forever("total_rows_BENTHIC_DB_{$this->surveyProgram->id}", $rowsPerSheet["BENTHIC_DB"]);
                    cache()->forever("total_rows_MOTILE_DB_{$this->surveyProgram->id}", $rowsPerSheet["MOTILE_DB"]);
                }
            },
            AfterImport::class => function (AfterImport $event) {
                cache(["end_date_{$this->surveyProgram->id}" => now()], now()->addMinute());
                cache()->forget("total_rows_DIVE_SITE_METADATA_{$this->surveyProgram->id}");
                cache()->forget("total_rows_BENTHIC_TAXAS_{$this->surveyProgram->id}");
                cache()->forget("total_rows_MOTILE_TAXAS_{$this->surveyProgram->id}");
                cache()->forget("total_rows_BENTHIC_DB_{$this->surveyProgram->id}");
                cache()->forget("total_rows_MOTILE_DB_{$this->surveyProgram->id}");
                cache()->forget("start_date_{$this->surveyProgram->id}");
                cache()->forget("current_row_DIVE_SITE_METADATA__{$this->surveyProgram->id}");
                cache()->forget("current_row_BENTHIC_TAXAS__{$this->surveyProgram->id}");
                cache()->forget("current_row_MOTILE_TAXAS__{$this->surveyProgram->id}");
                cache()->forget("current_row_BENTHIC_DB__{$this->surveyProgram->id}");
                cache()->forget("current_row_MOTILE_DB__{$this->surveyProgram->id}");
            },
            ImportFailed::class => function (ImportFailed $event) {
                $this->surveyProgram->delete();
                logger("import failed");
                cache()->forget("total_rows_DIVE_SITE_METADATA_{$this->surveyProgram->id}");
                cache()->forget("total_rows_BENTHIC_TAXAS_{$this->surveyProgram->id}");
                cache()->forget("total_rows_MOTILE_TAXAS_{$this->surveyProgram->id}");
                cache()->forget("total_rows_BENTHIC_DB_{$this->surveyProgram->id}");
                cache()->forget("total_rows_MOTILE_DB_{$this->surveyProgram->id}");
                cache()->forget("start_date_{$this->surveyProgram->id}");
                cache()->forget("current_row_DIVE_SITE_METADATA_{$this->surveyProgram->id}");
                cache()->forget("current_row_BENTHIC_TAXAS_{$this->surveyProgram->id}");
                cache()->forget("current_row_MOTILE_TAXAS_{$this->surveyProgram->id}");
                cache()->forget("current_row_BENTHIC_DB_{$this->surveyProgram->id}");
                cache()->forget("current_row_MOTILE_DB_{$this->surveyProgram->id}");

                if (get_class($event->getException()) === 'Maatwebsite\Excel\Validators\ValidationException') {
                    $failures = $event->getException()->failures();
                    $errors = array_map(function ($el) {
                        return str_replace(":row", "ROW " . $el->row(), $el->errors()[0]);
                    }, $failures);

                    cache(["errors_DIVE_SITE_METADATA_{$this->surveyProgram->id}" => $errors], now()->addMinutes(60));
                } else {
                    $exception = $event->getException();
                    logger($exception);
                    cache(["errors_DIVE_SITE_METADATA_{$this->surveyProgram->id}" => [$exception->getMessage() . " on " . $exception->getFile() . ':' . $exception->getLine()]], now()->addMinutes(60));
                }
            },
        ];
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
