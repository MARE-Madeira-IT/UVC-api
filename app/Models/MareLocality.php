<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class MareLocality extends Model
{
    use FiltersRecords;
    protected $fillable = ['name', 'code', 'project_id'];

    protected $table = 'mare_localities';

    public function reports()
    {
        return $this->hasMany(MareReport::class);
    }

    public function sites()
    {
        return $this->hasMany(MareSite::class, 'locality_id');
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class, 'project_id');
    }
}
