<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Benthic extends Model
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
        return $this->belongsTo(Report::class);
    }

    public function taxa()
    {
        return $this->belongsTo(Taxa::class);
    }

    public function substrate()
    {
        return $this->belongsTo(Substrate::class);
    }
}
