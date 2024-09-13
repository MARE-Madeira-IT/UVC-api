<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrophicGuild extends Model
{
    public function taxas()
    {
        return $this->belongsToMany(Taxa::class, 'taxa_has_trophic_guilds');
    }
}
