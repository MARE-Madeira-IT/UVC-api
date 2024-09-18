<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class SurveyProgram extends Model
{
    use FiltersRecords;

    protected $table = 'wave_mare.survey_programs';
    protected $connection = 'mysql';

    protected $fillable = [
        "name",
        "description",
        "public",
        "geographic_area",
        "start_period",
        "end_period",
        "stage",
        "community_size",
        "contact"
    ];

    public function sites()
    {
        return $this->hasManyThrough(SurveyProgram::class, Locality::class, 'survey_program_id');
    }

    public function localities()
    {
        return $this->hasMany(Locality::class, 'survey_program_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wave_mare.survey_program_has_users', 'survey_program_id', 'user_id');
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
