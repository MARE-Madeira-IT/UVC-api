<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class WorkspaceFilters extends QueryFilters
{
    public function name($string)
    {
        $this->query->where('name', 'like', '%' . $string . '%');
    }
}
