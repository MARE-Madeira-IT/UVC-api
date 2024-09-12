<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareInvite extends Model
{

    protected $fillable = [
        "project_id",
        "user_id",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(MareProject::class, 'project_id');
    }
}
