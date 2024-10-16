<?php

namespace App\Observers;

use App\Models\Locality;

class LocalityObserver
{
    private function deleteExports(Locality $locality)
    {
        $surveyProgram = $locality->surveyProgram;

        $surveyProgram->exports()->delete();
    }

    /**
     * Handle the Benthic "created" event.
     */
    public function created(Locality $locality): void
    {
        $this->deleteExports($locality);
    }

    /**
     * Handle the Locality "updated" event.
     */
    public function updated(Locality $locality): void
    {
        $this->deleteExports($locality);
    }

    /**
     * Handle the Locality "deleted" event.
     */
    public function deleted(Locality $locality): void
    {
        $this->deleteExports($locality);
    }
}
