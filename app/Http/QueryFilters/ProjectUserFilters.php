<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class ProjectUserFilters extends QueryFilters
{
    public function project($id)
    {
        $this->query->where('project_id', $id);
    }
}
