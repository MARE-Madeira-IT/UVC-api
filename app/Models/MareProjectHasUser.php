<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MareProjectHasUser extends Model
{
    protected $fillable = [
        "project_id",
        "user_id",
        "active",
    ];

    public function permissions()
    {
        return $this->belongsToMany(MarePermission::class, 'wave_mare.mare_project_user_has_permissions', 'mare_project_has_users_id', 'permission_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
