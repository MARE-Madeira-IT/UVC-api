<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeCategory extends Model
{

    protected $fillable = ['name'];


    public function motiles()
    {
        return $this->hasMany(Motile::class);
    }
}
