<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareTaxa extends Model
{
    use FiltersRecords;
    protected $fillable = ['name', 'genus', 'species', 'phylum', 'project_id', 'category_id', 'photo_url', 'validated'];


    public function category()
    {
        return $this->belongsTo(MareTaxaCategory::class);
    }

    public function indicators()
    {
        return $this->belongsToMany(MareIndicator::class, 'mare_taxa_has_indicators', 'taxa_id', 'indicator_id')->withPivot('name');
    }

    public function benthics()
    {
        return $this->hasMany(MareBenthic::class);
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class);
    }
}
