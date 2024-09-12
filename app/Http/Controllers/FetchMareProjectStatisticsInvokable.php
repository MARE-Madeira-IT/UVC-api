<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MareProject;
use App\Models\MareProjectHasUser;
use App\Models\MareReport;
use App\Models\MareSite;
use App\Models\MareTaxa;
use Illuminate\Http\Request;

class FetchMareProjectStatisticsInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(MareProject $project)
    {
        $members = MareProjectHasUser::where('project_id', $project->id)->count();
        $reports = MareReport::where('project_id', $project->id)->count();
        $sites = MareSite::whereHas('locality', function ($query) use ($project) {
            $query->where('project_id', $project->id);
        })->count();
        $taxa = MareTaxa::where('project_id', $project->id)->count();


        return response()->json([
            'data' => [
                'members' => $members,
                'reports' => $reports,
                'sites' => $sites,
                'taxa' => $taxa,
            ]
        ], 200);
        return $project;
    }
}
