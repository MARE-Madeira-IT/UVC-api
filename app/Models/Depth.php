<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Depth extends Model
{
    use FiltersRecords;

    protected $fillable = ['name', 'survey_program_id'];


    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }
}
