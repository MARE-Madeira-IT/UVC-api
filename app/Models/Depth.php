<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depth extends Model
{
    use FiltersRecords, SoftDeletes;

    protected $fillable = ['name', 'survey_program_id', 'code'];


    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }
}
