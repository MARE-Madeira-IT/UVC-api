<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyProgram extends Model
{
    use FiltersRecords, SoftDeletes;

    protected $table = 'wave_mare.survey_programs';
    protected $connection = 'mysql';

    protected $fillable = [
        "project_id",
        "name",
        "description",
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sites()
    {
        return $this->hasManyThrough(SurveyProgram::class, Locality::class, 'survey_program_id');
    }

    public function localities()
    {
        return $this->hasMany(Locality::class, 'survey_program_id');
    }

    public function surveyProgramUsers()
    {
        return $this->hasMany(SurveyProgramUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wave_mare.survey_program_users', 'survey_program_id', 'user_id');
    }

    public function taxas()
    {
        return $this->hasMany(Taxa::class, 'survey_program_id');
    }

    public function taxaCategories()
    {
        return $this->hasMany(TaxaCategory::class, 'survey_program_id');
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class, 'survey_program_id');
    }

    public function depths()
    {
        return $this->hasMany(Depth::class, 'survey_program_id');
    }

    public function functions()
    {
        return $this->hasMany(SurveyProgramFunction::class, 'survey_program_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'survey_program_id');
    }
}
