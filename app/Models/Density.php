<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Density extends Model
{


    public function motiles()
    {
        return $this->belongsToMany(Motile::class, 'motile_has_densities');
    }
}
