<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\ReportFilters;
use App\Http\Resources\ReportMapResource;
use App\Models\Report;
use Illuminate\Http\Request;

class FetchReportCoordinatesInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ReportFilters $filters)
    {
        return ReportMapResource::collection(Report::filterBy($filters)->latest()->take(100)->get());
    }
}
