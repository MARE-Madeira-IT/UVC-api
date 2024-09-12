<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareSubstrate extends Model
{
    use FiltersRecords;

    protected $fillable = ['name'];

    public function reports()
    {
        return $this->belongsToMany(MareReport::class, 'mare_report_has_substrates');
    }

    public function benthics()
    {
        return $this->hasMany(MareBenthic::class);
    }
}
