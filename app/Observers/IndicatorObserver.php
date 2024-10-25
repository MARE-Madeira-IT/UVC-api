<?php

namespace App\Observers;

use App\Models\Indicator;

class IndicatorObserver
{
    public function deleted(Indicator $indicator): void
    {
        $indicator->update([
            'name' => time() . '|' . $indicator->name,
        ]);
    }
}
