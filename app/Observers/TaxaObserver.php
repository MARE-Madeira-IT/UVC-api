<?php

namespace App\Observers;

use App\Models\Taxa;

class TaxaObserver
{
    private function deleteExports(Taxa $taxa)
    {
        $surveyProgram = $taxa->surveyProgram;

        $surveyProgram->exports()->delete();
    }

    /**
     * Handle the Taxa "created" event.
     */
    public function created(Taxa $taxa): void
    {
        $this->deleteExports($taxa);
    }

    /**
     * Handle the Taxa "updated" event.
     */
    public function updated(Taxa $taxa): void
    {
        $this->deleteExports($taxa);
    }

    /**
     * Handle the Taxa "deleted" event.
     */
    public function deleted(Taxa $taxa): void
    {
        $this->deleteExports($taxa);

        $taxa->update([
            'name' => time() . '|' . $taxa->name
        ]);
    }
}
