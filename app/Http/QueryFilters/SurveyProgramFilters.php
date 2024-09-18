<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class SurveyProgramFilters extends QueryFilters
{
    public function name($string)
    {
        $this->query->where('name', 'like', '%' . $string . '%');
    }

    public function stage($array)
    {
        $this->query->whereIn('stage', $array);
    }
}
