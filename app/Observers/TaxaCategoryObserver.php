<?php

namespace App\Observers;

use App\Models\TaxaCategory;

class TaxaCategoryObserver
{
    public function deleted(TaxaCategory $taxaCategory): void
    {
        $taxaCategory->update([
            'name' => time() . '|' . $taxaCategory->name,
        ]);
    }
}
