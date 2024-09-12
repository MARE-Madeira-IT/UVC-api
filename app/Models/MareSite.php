<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareSite extends Model
{
    protected $fillable = ['name', 'code', 'locality_id'];



    public function locality()
    {
        return $this->belongsTo(MareLocality::class, 'locality_id');
    }

    public function reports()
    {
        return $this->hasMany(MareReport::class);
    }
}
