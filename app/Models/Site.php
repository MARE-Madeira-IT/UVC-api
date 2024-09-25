<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

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
