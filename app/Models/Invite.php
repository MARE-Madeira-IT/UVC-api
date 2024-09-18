<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{

    protected $fillable = [
        "survey_program_id",
        "user_id",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class, 'survey_program_id');
    }
}
