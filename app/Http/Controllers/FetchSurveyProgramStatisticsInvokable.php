<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SurveyProgramUser;
use App\Models\Report;
use App\Models\Site;
use App\Models\SurveyProgram;
use App\Models\Taxa;

class FetchSurveyProgramStatisticsInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SurveyProgram $surveyProgram)
    {
        $members = SurveyProgramUser::where('survey_program_id', $surveyProgram->id)->count();
        $reports = Report::where('survey_program_id', $surveyProgram->id)->count();
        $sites = Site::whereHas('locality', function ($query) use ($surveyProgram) {
            $query->where('survey_program_id', $surveyProgram->id);
        })->count();
        $taxa = Taxa::where('survey_program_id', $surveyProgram->id)->count();


        return response()->json([
            'data' => [
                'members' => $members,
                'reports' => $reports,
                'sites' => $sites,
                'taxa' => $taxa,
            ]
        ], 200);
    }
}
