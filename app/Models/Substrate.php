<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Substrate extends Model
{
    use FiltersRecords, SoftDeletes;

    protected $fillable = ['name'];

    public function benthics()
    {
        return $this->hasMany(Benthic::class);
    }
}
