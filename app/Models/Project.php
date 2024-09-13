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
        return $this->hasManyThrough(Project::class, Locality::class, 'project_id');
    }

    public function localities()
    {
        return $this->hasMany(Locality::class, 'project_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wave_mare.project_has_users', 'project_id', 'user_id');
    }

    public function taxas()
    {
        return $this->hasMany(Taxa::class, 'project_id');
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class, 'project_id');
    }

    public function depths()
    {
        return $this->hasMany(Depth::class, 'project_id');
    }

    public function functions()
    {
        return $this->hasMany(ProjectFunction::class, 'project_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'project_id');
    }
}
