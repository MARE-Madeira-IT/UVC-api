<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use FiltersRecords, SoftDeletes;

    protected $table = 'wave_mare.workspaces';
    protected $connection = 'mysql';

    protected $fillable = [
        "name",
        "description",

    ];

    public function surveyPrograms()
    {
        return $this->hasManyThrough(SurveyProgram::class, Project::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wave_mare.workspace_users', 'workspace_id', 'user_id');
    }

    public function workspaceUsers()
    {
        return $this->hasMany(WorkspaceUser::class);
    }
}
