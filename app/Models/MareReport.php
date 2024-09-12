<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareReport extends Model
{
    use FiltersRecords;


    protected $fillable = [
        "code",
        "date",
        "transect",
        "daily_dive",
        "replica",
        "latitude",
        "longitude",
        "heading",
        "heading_direction",
        "dom_substrate",
        "site_area",
        "distance",
        "site_id",
        "depth_id",
        "project_id",
        "time",
        "surveyed_area",
    ];

    public function benthics()
    {
        return $this->hasMany(MareBenthic::class, 'report_id');
    }

    public function mareReportMotiles()
    {
        return $this->hasMany(MareReportMotile::class, 'report_id');
    }

    public function functions()
    {
        return $this->belongsToMany(MareFunction::class, 'mare_report_has_functions', 'report_id', 'function_id')->withPivot('user');
    }

    public function site()
    {
        return $this->belongsTo(MareSite::class);
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class);
    }

    public function depth()
    {
        return $this->belongsTo(MareDepth::class);
    }
}
