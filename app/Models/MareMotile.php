<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareMotile extends Model
{
    use FiltersRecords;

    protected $fillable = [
        'taxa_id',
        'size_category_id',
        'size',
        'ntotal',
        'density/1',
        'biomass/1',
        'mare_report_motile_id',
        'notes',
    ];

    public function taxa()
    {
        return $this->belongsTo(MareTaxa::class);
    }

    public function sizeCategory()
    {
        return $this->belongsTo(MareSizeCategory::class);
    }

    public function mareReportMotile()
    {
        return $this->belongsTo(MareReportMotile::class, 'mare_report_motile_id');
    }
}
