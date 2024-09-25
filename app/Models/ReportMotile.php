<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportMotile extends Model
{
    use FiltersRecords, SoftDeletes;


    protected $fillable = [
        'type',
        'report_id',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function motiles()
    {
        return $this->hasMany(Motile::class, 'report_motile_id');
    }
}
