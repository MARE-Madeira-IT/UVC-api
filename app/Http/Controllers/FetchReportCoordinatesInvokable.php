<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

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
    public function __invoke(Request $request)
    {
        return Report::latest()->take(100)->get();
    }
}
