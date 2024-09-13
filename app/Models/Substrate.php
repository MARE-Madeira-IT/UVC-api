<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Substrate extends Model
{
    use FiltersRecords;

    protected $fillable = ['name'];

    public function reports()
    {
        return $this->belongsToMany(Report::class, 'report_has_substrates');
    }

    public function benthics()
    {
        return $this->hasMany(Benthic::class);
    }
}
