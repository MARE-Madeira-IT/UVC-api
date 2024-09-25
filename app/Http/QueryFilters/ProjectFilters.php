<?php

namespace App\Http\QueryFilters;

use Carbon\Carbon;
use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class ProjectFilters extends QueryFilters
{
    public function name($string)
    {
        $this->query->where('name', 'like', '%' . $string . '%');
    }

    public function stage($array)
    {
        $this->query->whereIn('stage', $array);
    }

    public function visibility($array)
    {
        if (in_array('Private', $array) && in_array('Public', $array)) {
            $this->query;
        } else if (in_array('Private', $array)) {
            $this->query->where('public', false);
        } else if (in_array('Public', $array)) {
            $this->query->where('public', true);
        }
    }

    public function date($array)
    {
        $startYear = Carbon::parse($array[0])->startOfYear();
        $endYear = Carbon::parse($array[1])->endOfYear();

        //works if it includes even just a segment of the period
        $this->query->whereYear('start_period', '<=', $endYear)->whereYear('end_period', '>=', $startYear);
    }

    public function communitySize($string)
    {
        $this->query->where('community_size', $string);
    }

    public function geographicArea($array)
    {
        $arrayAux = [];

        foreach ($array as $el) {
            if (str_contains($el, ',')) {
                $arrayAux = array_merge($arrayAux, explode(',', $el));
            } else {
                array_push($arrayAux, $el);
            }
        }

        $this->query->whereIn('geographic_area', $arrayAux);
    }

    public function workspace($ids)
    {
        $this->query->whereIn('workspace_id', $ids);
    }

    public function search($string)
    {
        $this->query->where(function ($query) use ($string) {
            $query->where('name', 'like', '%' . $string . '%')
                ->orWhere('description', 'like', '%' . $string . '%');
        });
    }
}
