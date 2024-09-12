<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareDensity extends Model
{


    public function motiles()
    {
        return $this->belongsToMany(MareMotile::class, 'mare_motile_has_densities');
    }
}
