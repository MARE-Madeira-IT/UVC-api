<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SurveyProgramHasUser extends Pivot
{
    public $incrementing = true;

    protected $table = 'wave_mare.survey_program_has_users';
    protected $connection = 'mysql';

    protected $fillable = [
        "id",
        "survey_program_id",
        "user_id",
        "active",
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'survey_program_user_has_permissions', 'survey_program_has_users_id', 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
