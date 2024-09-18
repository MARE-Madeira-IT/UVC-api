<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Taxa extends Model
{
    use FiltersRecords;
    protected $fillable = ['name', 'genus', 'species', 'phylum', 'survey_program_id', 'category_id', 'photo_url', 'validated'];


    public function category()
    {
        return $this->belongsTo(TaxaCategory::class);
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class, 'taxa_has_indicators', 'taxa_id', 'indicator_id')->withPivot('name');
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
