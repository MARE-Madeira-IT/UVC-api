<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SurveyProgramExportCompletedJob implements ShouldQueue
{
    use Queueable;

    private $filename, $export;

    /**
     * Create a new job instance.
     */
    public function __construct($export, $filename)
    {
        $this->filename = $filename;
        $this->export = $export;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->export->update([
            'state' => 'finished',
            'url' => $this->filename
        ]);
    }
}
