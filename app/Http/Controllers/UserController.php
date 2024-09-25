<?php

namespace App\Http\Controllers;

use App\Http\Resources\InviteResource;
use App\Models\ProjectUser;
use App\Models\SurveyProgramUser;
use App\Models\WorkspaceUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserInvites()
    {
        $current_page = LengthAwarePaginator::resolveCurrentPage();
        $per_page = 3;
        $user_id = Auth::id();

        $surveyProgramInvites = SurveyProgramUser::where('user_id', $user_id)
            ->where('accepted', false)
            ->get();
        $projectInvites = ProjectUser::where('user_id', $user_id)
            ->where('accepted', false)
            ->get();
        $workspaceInvites = WorkspaceUser::where('user_id', $user_id)
            ->where('accepted', false)
            ->get();

        $all_models = (collect([$surveyProgramInvites, $projectInvites, $workspaceInvites]))->flatten(0);

        $collection = (InviteResource::collection($all_models))->sortByDesc('id');
        $all_three_types_of_models = $collection->slice(($current_page - 1) * $per_page, $per_page)->all();

        $all_models = new LengthAwarePaginator($all_three_types_of_models, count($collection), $per_page);
        $all_models->withPath('');


        return response()->json([
            'data' => array_values($all_models->getCollection()->toArray()),
            'meta' => [
                "current_page" => $all_models->currentPage(),
                "per_page" => $all_models->perPage(),
                "total" => $all_models->total(),
            ]
        ], 200);
    }
}
