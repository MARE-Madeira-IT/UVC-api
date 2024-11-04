<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WorkspaceUser extends Pivot
{
    use FiltersRecords;

    public $incrementing = true;

    protected $table = 'uvc.workspace_users';
    protected $connection = 'mysql';

    protected $fillable = [
        "id",
        "workspace_id",
        "user_id",
        "active",
        "accepted"
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'workspace_user_has_permissions', 'workspace_user_id', 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
