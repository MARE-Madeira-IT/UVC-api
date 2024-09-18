<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class TaxaFilters extends QueryFilters
{
    public function survey_program($id)
    {
        $this->query->where('survey_program_id', $id);
    }

    public function search($string)
    {
        $this->query->where(function ($query) use ($string) {
            $query->where('name', 'like', '%' . $string . '%')
                ->orWhere('species', 'like', '%' . $string . '%')
                ->orWhere('genus', 'like', '%' . $string . '%')
                ->orWhere('phylum', 'like', '%' . $string . '%')
                ->orWhereHas('category', function ($query) use ($string) {
                    $query->where('name', 'like', '%' . $string . '%');
                });
        });
    }
}
