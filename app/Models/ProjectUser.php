<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    use FiltersRecords;

    public $incrementing = true;

    protected $table = 'uvc.project_users';
    protected $connection = 'mysql';

    protected $fillable = [
        "id",
        "project_id",
        "user_id",
        "active",
        "accepted"
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'project_user_has_permissions', 'project_user_id', 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
