<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class MemberFilters extends QueryFilters
{
    public function survey_program($id)
    {
        $this->query->where('survey_program_id', $id);
    }
}
