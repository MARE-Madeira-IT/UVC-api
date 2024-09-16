<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['name', 'code', 'locality_id', 'latitude', 'longitude'];



    public function locality()
    {
        return $this->belongsTo(Locality::class, 'locality_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
