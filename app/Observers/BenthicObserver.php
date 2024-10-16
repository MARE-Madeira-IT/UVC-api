<?php

namespace App\Observers;

use App\Models\Benthic;
use App\Models\SurveyProgram;

class BenthicObserver
{
    private function deleteExports(Benthic $benthic)
    {
        $surveyProgram = $benthic->report->surveyProgram;

        $surveyProgram->exports()->delete();
    }
    /**
     * Handle the Benthic "created" event.
     */
    public function created(Benthic $benthic): void
    {
        $this->deleteExports($benthic);
    }

    /**
     * Handle the Benthic "updated" event.
     */
    public function updated(Benthic $benthic): void
    {
        $this->deleteExports($benthic);
    }

    /**
     * Handle the Benthic "deleted" event.
     */
    public function deleted(Benthic $benthic): void
    {
        $this->deleteExports($benthic);
    }
}
