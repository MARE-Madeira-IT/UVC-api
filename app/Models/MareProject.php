<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareProject extends Model
{
    use FiltersRecords;

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
        return $this->hasManyThrough(MareProject::class, MareLocality::class, 'project_id');
    }

    public function localities()
    {
        return $this->hasMany(MareLocality::class, 'project_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wave_mare.mare_project_has_users', 'project_id', 'user_id');
    }

    public function taxas()
    {
        return $this->hasMany(MareTaxa::class, 'project_id');
    }

    public function indicators()
    {
        return $this->hasMany(MareIndicator::class, 'project_id');
    }

    public function depths()
    {
        return $this->hasMany(MareDepth::class, 'project_id');
    }

    public function functions()
    {
        return $this->hasMany(MareFunction::class, 'project_id');
    }

    public function reports()
    {
        return $this->hasMany(MareReport::class, 'project_id');
    }
}
