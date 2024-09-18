<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class TaxaCategory extends Model
{
    use FiltersRecords;

    protected $fillable = ['name', 'project_id'];

    public function taxas()
    {
        return $this->hasMany(Taxa::class, 'category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
