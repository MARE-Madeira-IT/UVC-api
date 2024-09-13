<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectHasUser extends Pivot
{
    public $incrementing = true;

    protected $table = 'wave_mare.project_has_users';
    protected $connection = 'mysql';

    protected $fillable = [
        "id",
        "project_id",
        "user_id",
        "active",
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'project_user_has_permissions', 'project_has_users_id', 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
