<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class MotileFilters extends QueryFilters
{
    public function surveyProgram($id)
    {
        $this->query->whereHas('report', function ($query) use ($id) {
            $query->where('survey_program_id', $id);
        });
    }

    public function search($string)
    {
        $this->query->whereHas('report', function ($query)  use ($string) {
            $query->where(function ($query) use ($string) {
                $query->where('code', 'like', '%' . $string . '%')
                    ->orWhereHas('site', function ($query) use ($string) {
                        $query->where('name', 'like', '%' . $string . '%')
                            ->orWhereHas('locality', function ($query) use ($string) {
                                $query->where('name', 'like', '%' . $string . '%');
                            });
                    });
            });
        });
    }
}
