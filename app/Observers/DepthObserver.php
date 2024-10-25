<?php

namespace App\Observers;

use App\Models\Depth;

class DepthObserver
{
    public function deleted(Depth $depth): void
    {
        $depth->update([
            'name' => time() . '|' . $depth->name,
            'code' => time() . '|' . $depth->code,
        ]);
    }
}
