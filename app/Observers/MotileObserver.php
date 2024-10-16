<?php

namespace App\Observers;

use App\Models\Motile;

class MotileObserver
{

    private function deleteExports(Motile $motile)
    {
        $surveyProgram = $motile->mareReportMotile->report->surveyProgram;

        $surveyProgram->exports()->delete();
    }

    /**
     * Handle the Motile "created" event.
     */
    public function created(Motile $motile): void
    {
        $this->deleteExports($motile);
    }

    /**
     * Handle the Motile "updated" event.
     */
    public function updated(Motile $motile): void
    {
        $this->deleteExports($motile);
    }

    /**
     * Handle the Motile "deleted" event.
     */
    public function deleted(Motile $motile): void
    {
        $this->deleteExports($motile);
    }
}
