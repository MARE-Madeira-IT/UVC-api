<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareSizeCategory extends Model
{

    protected $fillable = ['name'];


    public function motiles()
    {
        return $this->hasMany(MareMotile::class);
    }
}
