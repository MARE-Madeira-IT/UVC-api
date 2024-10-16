<?php

namespace App\Models;

use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use FiltersRecords;

    protected $fillable = [
        'survey_program_id',
        'url',
        'state',
        'date_from',
        'date_to',
    ];

    public function surveyProgram()
    {
        return $this->belongsTo(SurveyProgram::class);
    }

    public function taxas()
    {
        return $this->belongsToMany(Taxa::class, 'export_has_taxas', 'export_id', 'taxa_id');
    }

    public function taxaCategories()
    {
        return $this->belongsToMany(TaxaCategory::class, 'export_has_taxa_categories', 'export_id', 'taxa_category_id');
    }

    public function reports()
    {
        return $this->belongsToMany(Report::class, 'export_has_reports', 'export_id', 'report_id');
    }

    public function depths()
    {
        return $this->belongsToMany(Depth::class, 'export_has_depths', 'export_id', 'depth_id');
    }

    public function localities()
    {
        return $this->belongsToMany(Locality::class, 'export_has_localities', 'export_id', 'locality_id');
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'export_has_sites', 'export_id', 'site_id');
    }
}
