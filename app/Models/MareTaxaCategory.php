<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareTaxaCategory extends Model
{
    protected $fillable = ['name'];


    public function taxas()
    {
        return $this->hasMany(MareTaxa::class, 'category_id');
    }
}
