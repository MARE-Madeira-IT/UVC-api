<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use FiltersRecords, SoftDeletes;


    protected $fillable = [
        "code", //It's only used on the import, else getCode() is used
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
        "survey_program_id",
        "time",
        "surveyed_area",
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }


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
        return $this->belongsToMany(SurveyProgramFunction::class, 'report_has_functions', 'report_id', 'function_id')->withPivot('user');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }

    public function depth()
    {
        return $this->belongsTo(Depth::class);
    }

    public function getCode()
    {
        return $this->site->locality->code . "_" . $this->site->code . "_Time" . $this->time . "_D" . $this->depth->code . "_R" . $this->replica;
    }
}
