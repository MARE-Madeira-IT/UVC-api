<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorHasValue extends Model
{
    use HasFactory;

    protected $fillable = [
        "indicator_id",
        "name",
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
