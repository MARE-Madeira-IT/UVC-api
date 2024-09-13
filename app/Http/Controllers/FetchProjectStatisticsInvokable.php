<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectHasUser;
use App\Models\Report;
use App\Models\Site;
use App\Models\Taxa;

class FetchProjectStatisticsInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Project $project)
    {
        $members = ProjectHasUser::where('project_id', $project->id)->count();
        $reports = Report::where('project_id', $project->id)->count();
        $sites = Site::whereHas('locality', function ($query) use ($project) {
            $query->where('project_id', $project->id);
        })->count();
        $taxa = Taxa::where('project_id', $project->id)->count();


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
