<?php

namespace App\Observers;

use App\Models\Report;

class ReportObserver
{
    private function deleteExports(Report $report)
    {
        $surveyProgram = $report->surveyProgram;

        $surveyProgram->exports()->delete();
    }

    /**
     * Handle the Report "created" event.
     */
    public function created(Report $report): void
    {
        $this->deleteExports($report);
    }

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        $this->deleteExports($report);
    }

    /**
     * Handle the Report "deleted" event.
     */
    public function deleted(Report $report): void
    {
        $this->deleteExports($report);

        $report->update([
            'code' => time() . '|' . $report->code
        ]);
    }
}
