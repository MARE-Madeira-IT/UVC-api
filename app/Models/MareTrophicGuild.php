<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareTrophicGuild extends Model
{
    public function taxas()
    {
        return $this->belongsToMany(MareTaxa::class, 'mare_taxa_has_trophic_guilds');
    }
}
