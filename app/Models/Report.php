<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
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

    protected $casts = [
        "date" => "date",
    ];

    public function benthics()
    {
        return $this->hasMany(Benthic::class, 'report_id');
    }

    public function mareReportMotiles()
    {
        return $this->hasMany(ReportMotile::class, 'report_id');
    }

    public function functions()
    {
        return $this->belongsToMany(ProjectFunction::class, 'report_has_functions', 'report_id', 'function_id')->withPivot('user');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function depth()
    {
        return $this->belongsTo(Depth::class);
    }
}
