<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locality extends Model
{
    use FiltersRecords, SoftDeletes;
    
    protected $fillable = ['name', 'code', 'survey_program_id'];

    protected $table = 'localities';

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class, 'locality_id');
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class, 'survey_program_id');
    }
}
