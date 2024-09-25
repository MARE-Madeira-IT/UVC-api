<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxa extends Model
{
    use FiltersRecords, SoftDeletes;
    protected $fillable = ['name', 'genus', 'species', 'phylum', 'survey_program_id', 'category_id', 'photo_url', 'validated'];


    public function category()
    {
        return $this->belongsTo(TaxaCategory::class);
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class, 'taxa_has_indicators', 'taxa_id', 'indicator_id')->whereNull('taxa_has_indicators.deleted_at')->withPivot('name');
    }

    public function taxaHasIndicators()
    {
        return $this->hasMany(TaxaHasIndicator::class, 'taxa_id')->whereNull('taxa_has_indicators.deleted_at');
    }

    public function benthics()
    {
        return $this->hasMany(Benthic::class);
    }

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }
}
