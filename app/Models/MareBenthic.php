<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareBenthic extends Model
{
    use FiltersRecords;

    protected $fillable = [
        "p##",
        "substrate_id",
        "notes",
        "report_id",
        "taxa_id"
    ];

    public function report()
    {
        return $this->belongsTo(MareReport::class);
    }

    public function taxa()
    {
        return $this->belongsTo(MareTaxa::class);
    }

    public function substrate()
    {
        return $this->belongsTo(MareSubstrate::class);
    }
}
