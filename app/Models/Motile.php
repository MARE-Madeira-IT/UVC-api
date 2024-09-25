<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motile extends Model
{
    use FiltersRecords, SoftDeletes;

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
