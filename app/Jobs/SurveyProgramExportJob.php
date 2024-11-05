<?php

namespace App\Jobs;

use App\Exports\SurveyProgramExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SurveyProgramExportJob implements ShouldQueue
{
    use Queueable;

    private $newEntry, $requestArr, $filename;

    /**
     * Create a new job instance.
     */
    public function __construct($newEntry, $requestArr, $filename)
    {
        $this->newEntry = $newEntry;
        $this->requestArr = $requestArr;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new SurveyProgramExport($this->newEntry, $this->requestArr))->queue($this->filename, 'local', \Maatwebsite\Excel\Excel::XLSX)
            ->chain([
                new SurveyProgramExportCompletedJob($this->newEntry, $this->filename)
            ]);
    }
}
