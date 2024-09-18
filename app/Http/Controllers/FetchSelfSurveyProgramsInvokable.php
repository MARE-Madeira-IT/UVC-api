<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyProgramResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FetchSelfSurveyProgramsInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        if ($user = Auth::user()) {

            return SurveyProgramResource::collection($user->surveyPrograms()->wherePivot('active', true)->get());
        } else
            return ["data" => []];
    }
}
