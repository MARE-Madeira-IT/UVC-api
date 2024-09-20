<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class WorkspaceUserFilters extends QueryFilters
{
    public function workspace($id)
    {
        $this->query->where('workspace_id', $id);
    }
}
