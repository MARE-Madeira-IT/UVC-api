<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxaCategory extends Model
{
    protected $fillable = ['name'];


    public function taxas()
    {
        return $this->hasMany(Taxa::class, 'category_id');
    }
}
