<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends Model
{
    use FiltersRecords, SoftDeletes;


    protected $fillable = [
        "name",
        "type", //"number", "text", "select"
        "survey_program_id"
    ];

    public function taxas()
    {
        return $this->belongsToMany(Taxa::class, 'taxa_has_indicators', 'indicator_id', 'taxa_id')->whereNull('taxa_has_indicators.deleted_at');
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }

    public function indicatorValues()
    {
        return $this->hasMany(IndicatorHasValue::class);
    }
}
