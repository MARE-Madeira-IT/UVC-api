<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareDepth extends Model
{
    use FiltersRecords;

    protected $fillable = ['name', 'project_id'];


    public function reports()
    {
        return $this->hasMany(MareReport::class);
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class);
    }
}
