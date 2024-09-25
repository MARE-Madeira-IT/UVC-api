<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyProgramFunction extends Model
{
    use FiltersRecords, SoftDeletes;


    protected $fillable = [
        "name", "survey_program_id"
    ];

    public function reports()
    {
        return $this->belongsToMany(Report::class, 'report_has_functions')->withPivot('user');
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }
}
