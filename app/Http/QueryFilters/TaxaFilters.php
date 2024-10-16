<?php

namespace App\Http\QueryFilters;

use Carbon\Carbon;
use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class TaxaFilters extends QueryFilters
{
    public function surveyProgram($id)
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

    public function reports($ids)
    {
        $this->query->whereHas("benthics", function ($q) use ($ids) {
            $q->whereIn("report_id", $ids);
        })->orWhereHas("motiles", function ($q) use ($ids) {
            $q->whereHas('mareReportMotile', function ($q1) use ($ids) {
                $q1->whereIn("report_id", $ids);
            });
        });
    }

    public function dates($dates)
    {
        $this->query->whereHas("benthics", function ($q1) use ($dates) {
            $q1->whereHas("report", function ($q2) use ($dates) {
                $q2->whereBetween("date", [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
            });
        })->orWhereHas("motiles", function ($q1) use ($dates) {

            $q1->whereHas('mareReportMotile', function ($q1) use ($dates) {
                $q1->whereHas("report", function ($q2) use ($dates) {
                    $q2->whereBetween("date", [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
                });
            });
        });
    }

    public function depths($ids)
    {
        $this->query->whereHas("benthics", function ($q1) use ($ids) {
            $q1->whereHas("report", function ($q2) use ($ids) {
                $q2->whereIn("depth_id", $ids);
            });
        })->orWhereHas("motiles", function ($q1) use ($ids) {

            $q1->whereHas('mareReportMotile', function ($q1) use ($ids) {
                $q1->whereHas("report", function ($q2) use ($ids) {
                    $q2->whereIn("depth_id", $ids);
                });
            });
        });
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

        $this->query->whereHas("benthics", function ($q1) use ($localityIds, $siteIds) {
            $q1->whereHas("report", function ($q2) use ($localityIds, $siteIds) {
                $q2->whereHas("site", function ($q3) use ($localityIds) {
                    $q3->whereIn("locality_id", $localityIds);
                });

                $q2->orWhereIn("site_id", $siteIds);
            });
        })->orWhereHas("motiles", function ($q1) use ($localityIds, $siteIds) {
            $q1->whereHas('mareReportMotile', function ($q1) use ($localityIds, $siteIds) {
                $q1->whereHas("report", function ($q2) use ($localityIds, $siteIds) {
                    $q2->whereHas("site", function ($q3) use ($localityIds) {
                        $q3->whereIn("locality_id", $localityIds);
                    });

                    $q2->orWhereIn("site_id", $siteIds);
                });
            });
        });
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


        $this->query->whereIn('id', $taxaIds)
            ->orWhereIn("category_id", $categoryIds);
    }
}
