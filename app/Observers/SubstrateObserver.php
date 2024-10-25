<?php

namespace App\Observers;

use App\Models\Substrate;

class SubstrateObserver
{
    public function deleted(Substrate $substrate): void
    {
        $substrate->update([
            'name' => time() . '|' . $substrate->name,
        ]);
    }
}
