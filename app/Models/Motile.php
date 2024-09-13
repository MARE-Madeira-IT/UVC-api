<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Motile extends Model
{
    use FiltersRecords;

    protected $fillable = [
        'taxa_id',
        'size_category_id',
        'size',
        'ntotal',
        'density/1',
        'biomass/1',
        'report_motile_id',
        'notes',
    ];

    public function taxa()
    {
        return $this->belongsTo(Taxa::class);
    }

    public function sizeCategory()
    {
        return $this->belongsTo(SizeCategory::class);
    }

    public function mareReportMotile()
    {
        return $this->belongsTo(ReportMotile::class, 'report_motile_id');
    }
}
