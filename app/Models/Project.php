<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use FiltersRecords;

    protected $table = 'wave_mare.projects';
    protected $connection = 'mysql';

    protected $fillable = [
        "workspace_id",
        "name",
        "description",
        "contact",
        "geographic_area",
        "start_period",
        "end_period",
        "stage",
        "community_size",
        "public",
    ];

    public function surveyPrograms()
    {
        return $this->hasMany(SurveyProgram::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wave_mare.project_users', 'project_id', 'user_id');
    }
}
