<?php

namespace App\Observers;

use App\Models\Site;

class SiteObserver
{
    private function deleteExports(Site $site)
    {
        $surveyProgram = $site->locality->surveyProgram;

        $surveyProgram->exports()->delete();
    }

    /**
     * Handle the Site "created" event.
     */
    public function created(Site $site): void
    {
        $this->deleteExports($site);
    }

    /**
     * Handle the Site "updated" event.
     */
    public function updated(Site $site): void
    {
        $this->deleteExports($site);
    }

    /**
     * Handle the Site "deleted" event.
     */
    public function deleted(Site $site): void
    {
        $this->deleteExports($site);
    }
}
