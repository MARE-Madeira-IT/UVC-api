<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class SubstrateFilters extends QueryFilters
{
    public function surveyProgram($id)
    {
        $this->query->where('survey_program_id', $id);
    }

    public function search($string)
    {
        $this->query->where(function ($query) use ($string) {
            $query->where('name', 'like', '%' . $string . '%');
        });
    }
}
