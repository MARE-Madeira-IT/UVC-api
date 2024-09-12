<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareFunction extends Model
{
    use FiltersRecords;


    protected $fillable = [
        "name", "project_id"
    ];

    public function reports()
    {
        return $this->belongsToMany(MareReport::class, 'mare_report_has_functions')->withPivot('user');
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class);
    }
}
