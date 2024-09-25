<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxaHasIndicator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "taxa_id",
        "indicator_id",
    ];

    public function taxas()
    {
        return $this->belongsTo(Taxa::class);
    }

    public function indicators()
    {
        return $this->belongsTo(Indicator::class);
    }
}
