<?php

namespace App\Http\Controllers;

use App\Http\QueryFilters\WorkspaceUserFilters;
use App\Http\Resources\WorkspaceUserResource;
use App\Models\Permission;
use App\Models\ProjectUser;
use App\Models\SurveyProgramUser;
use App\Models\User;
use App\Models\WorkspaceUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceUserController extends Controller
{
    public function index(WorkspaceUserFilters $filters)
    {
        return WorkspaceUserResource::collection(
            WorkspaceUser::filterBy($filters)->paginate(10)
        );
    }

    public function getUserInvites()
    {
        return WorkspaceUserResource::collection(
            WorkspaceUser::where('user_id', Auth::id())->where('accepted', false)->get()
        );
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $workspaceUser = WorkspaceUser::create([
                "workspace_id" => $request->workspace_id,
                "user_id" => $user->id
            ]);

            $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
            if ($request->create) {
                array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
            }

            if ($request->edit) {
                array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
                array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
            }

            $workspaceUser->permissions()->attach($permissionsToAdd);

            return response()->json([
                'data' => new WorkspaceUserResource(
                    $workspaceUser
                )
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is no registered user with that email',
            ], 422);
        }
    }

    public function acceptInvite(WorkspaceUser $workspaceUser, Request $request)
    {
        if ($request->status == 1) {
            if ($workspaceUser) {
                $workspaceUser->update([
                    'active' => true,
                    'accepted' => true,
                ]);

                foreach ($workspaceUser->workspace->projects as $project) {
                    ProjectUser::updateOrCreate(
                        [
                            'user_id' => Auth::id(),
                            'project_id' => $project->id,
                        ],
                        [
                            'user_id' => Auth::id(),
                            'project_id' => $project->id,
                            'accepted' => true,
                            'active' => true,
                        ]
                    );

                    foreach ($project->surveyPrograms as $surveyProgram) {
                        SurveyProgramUser::updateOrCreate(
                            [
                                'user_id' => Auth::id(),
                                'survey_program_id' => $surveyProgram->id,
                            ],
                            [
                                'user_id' => Auth::id(),
                                'survey_program_id' => $surveyProgram->id,
                                'accepted' => true,
                                'active' => true,
                            ]
                        );
                    }
                }
            }
        }

        return response()->json(["message" => "Accepted successfully"], 200);
    }

    public function update(WorkspaceUser $workspaceUser, Request $request)
    {
        $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
        if ($request->create) {
            array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
        }

        if ($request->edit) {
            array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
            array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
        }
        $workspaceUser->permissions()->detach();

        $workspaceUser->permissions()->attach($permissionsToAdd);

        return response()->json([
            'success' => true,
            'message' => 'The user permissions have been updated',
            'data' => new WorkspaceUserResource($workspaceUser),
        ], 201);
    }

    public function destroy(WorkspaceUser $workspaceUser)
    {
        $workspaceUser->delete();

        return response()->json(null, 204);
    }
}
