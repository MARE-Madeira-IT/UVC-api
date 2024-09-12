<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\MareReport;
use Illuminate\Http\Request;

class FetchMareReportCoordinatesInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return MareReport::latest()->take(100)->get();
    }
}
