<?php

namespace App\Http\QueryFilters;

use Carbon\Carbon;
use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class ReportFilters extends QueryFilters
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

    public function reports($ids)
    {
        $this->query->whereIn("id", $ids);
    }

    public function dates($dates)
    {
        $this->query->whereBetween("date", [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
    }

    public function depths($ids)
    {
        $this->query->whereIn("depth_id", $ids);
    }

    public function sites($ids)
    {
        $localityIds = array_map(function ($el) {
            return (int) $el[0];
        }, array_filter($ids, function ($el) {
            return count($el) === 1;
        }));

        $siteIds = array_map(function ($el) {
            return (int) $el[1];
        }, array_filter($ids, function ($el) {
            return count($el) > 1;
        }));

        $this->query->whereHas("site", function ($query) use ($localityIds) {
            $query->whereIn("locality_id", $localityIds);
        });

        $this->query->orWhereIn("site_id", $siteIds);
    }

    public function taxas($ids)
    {
        $categoryIds = array_map(function ($el) {
            return (int) $el[0];
        }, array_filter($ids, function ($el) {
            return count($el) === 1;
        }));

        $taxaIds = array_map(function ($el) {
            return (int) $el[1];
        }, array_filter($ids, function ($el) {
            return count($el) > 1;
        }));

        $this->query->whereHas("benthics", function ($q) use (
            $categoryIds,
            $taxaIds
        ) {
            $q->whereHas("taxa", function ($q2) use (
                $categoryIds,
                $taxaIds
            ) {
                $q2->whereIn('id', $taxaIds)
                    ->orWhereIn("category_id", $categoryIds);
            });
        })
            ->orWhereHas("mareReportMotiles", function ($q) use (
                $categoryIds,
                $taxaIds
            ) {
                $q->whereHas("motiles", function ($q2) use (
                    $categoryIds,
                    $taxaIds
                ) {
                    $q2->whereHas("taxa", function ($q3) use (
                        $categoryIds,
                        $taxaIds
                    ) {
                        $q3->whereIn('id', $taxaIds)
                            ->orWhereIn("category_id", $categoryIds);
                    });
                });
            });
    }
}
