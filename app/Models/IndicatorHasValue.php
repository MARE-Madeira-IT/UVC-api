<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicatorHasValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "indicator_id",
        "name",
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
