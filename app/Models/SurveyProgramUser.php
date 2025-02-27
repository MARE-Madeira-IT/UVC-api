<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SurveyProgramUser extends Pivot
{
    use FiltersRecords;

    public $incrementing = true;

    protected $table = 'uvc.survey_program_users';
    protected $connection = 'mysql';

    protected $fillable = [
        "id",
        "survey_program_id",
        "user_id",
        "active",
        "accepted"
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'survey_program_user_has_permissions', 'survey_program_user_id', 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }
}
