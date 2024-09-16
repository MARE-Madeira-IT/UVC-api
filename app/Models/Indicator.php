<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use FiltersRecords;


    protected $fillable = [
        "name",
        "type",
        "project_id"
    ];

    public function taxas()
    {
        return $this->belongsToMany(Taxa::class, 'taxa_has_indicators', 'indicator_id', 'taxa_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function indicatorValues()
    {
        return $this->hasMany(IndicatorHasValue::class);
    }
}
