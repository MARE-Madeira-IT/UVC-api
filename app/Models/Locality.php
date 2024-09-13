<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    use FiltersRecords;
    protected $fillable = ['name', 'code', 'project_id'];

    protected $table = 'localities';

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class, 'locality_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
