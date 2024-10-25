<?php

namespace App\Observers;

use App\Models\SizeCategory;

class SizeCategoryObserver
{
    public function deleted(SizeCategory $sizeCategory): void
    {
        $sizeCategory->update([
            'name' => time() . '|' . $sizeCategory->name,
        ]);
    }
}
