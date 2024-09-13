<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Depth extends Model
{
    use FiltersRecords;

    protected $fillable = ['name', 'project_id'];


    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
