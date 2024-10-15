<?php

namespace App\Http\QueryFilters;

use Carbon\Carbon;
use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class BenthicsFilters extends QueryFilters
{
    public function surveyProgram($id)
    {
        $this->query->where('survey_program_id', $id);
    }

    public function search($string)
    {
        $this->query->where(function ($query) use ($string) {
            $query->where('code', 'like', '%' . $string . '%')
                ->orWhereHas('site', function ($query) use ($string) {
                    $query->where('name', 'like', '%' . $string . '%')
                        ->orWhereHas('locality', function ($query) use ($string) {
                            $query->where('name', 'like', '%' . $string . '%');
                        });
                });
        });
    }

    public function reportId($ids)
    {
        $this->query->whereIn("report_id", $ids);
    }

    public function date($dates)
    {
        $this->query->whereHas("report", function ($q) use ($dates) {
            $q->whereBetween("date", [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
        });
    }

    public function depthId($ids)
    {
        $this->query->whereHas("report", function ($q) use ($ids) {
            $q->whereIn("depth_id", $ids);
        });
    }

    public function site($ids)
    {
        $localityIds = array_map(function ($el) {
            return (int) $el;
        }, array_filter($ids, function ($el) {
            return !str_contains($el, ',');
        }));

        $siteIds = array_map(function ($el) {
            return (int) explode(',', $el)[1];
        }, array_filter($ids, function ($el) {
            return str_contains($el, ',');
        }));

        $this->query->whereHas("report", function ($q) use ($siteIds, $localityIds) {
            $q->whereHas("site", function ($q2) use ($localityIds) {
                $q2->whereIn("locality_id", $localityIds);
            });

            $q->orWhereIn("site_id", $siteIds);
        });
    }

    public function taxas($ids)
    {
        $categoryIds = array_map(function ($el) {
            return (int) $el;
        }, array_filter($ids, function ($el) {
            return !str_contains($el, ',');
        }));

        $taxaIds = array_map(function ($el) {
            return (int) explode(',', $el)[1];
        }, array_filter($ids, function ($el) {
            return str_contains($el, ',');
        }));

        $this->query->whereHas("taxa", function ($q2) use (
            $categoryIds,
            $taxaIds
        ) {
            $q2->whereIn('id', $taxaIds)
                ->orWhereIn("category_id", $categoryIds);
        });
    }
}
