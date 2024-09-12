<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareIndicator extends Model
{
    use FiltersRecords;


    protected $fillable = [
        "name", "project_id"
    ];

    public function taxas()
    {
        return $this->belongsToMany(MareTaxa::class, 'mare_taxa_has_indicators', 'indicator_id', 'taxa_id');
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class);
    }
}
