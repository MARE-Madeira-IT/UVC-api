<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxaCategory extends Model
{
    use FiltersRecords, SoftDeletes;

    protected $fillable = ['name', 'survey_program_id'];

    public function taxas()
    {
        return $this->hasMany(Taxa::class, 'category_id');
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }
}
