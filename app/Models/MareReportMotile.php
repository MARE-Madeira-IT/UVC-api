<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareReportMotile extends Model
{
    use FiltersRecords;


    protected $fillable = [
        'type',
        'report_id',
    ];

    public function report()
    {
        return $this->belongsTo(MareReport::class);
    }

    public function motiles()
    {
        return $this->hasMany(MareMotile::class, 'mare_report_motile_id');
    }
}
