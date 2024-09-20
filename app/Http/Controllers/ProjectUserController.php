<?php

namespace App\Http\Controllers;

use App\Http\QueryFilters\ProjectUserFilters;
use App\Http\Resources\ProjectUserResource;
use App\Models\Permission;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectUserController extends Controller
{
    public function index(ProjectUserFilters $filters)
    {
        return ProjectUserResource::collection(
            ProjectUser::filterBy($filters)->paginate(10)
        );
    }

    public function getUserInvites()
    {
        return ProjectUserResource::collection(
            ProjectUser::where('user_id', Auth::id())->where('accepted', false)->get()
        );
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $projectUser = ProjectUser::create([
                "project_id" => $request->project_id,
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

            $projectUser->permissions()->attach($permissionsToAdd);

            return response()->json([
                'data' => new ProjectUserResource(
                    $projectUser
                )
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is no registered user with that email',
            ], 422);
        }
    }

    public function acceptInvite(ProjectUser $projectUser, Request $request)
    {
        if ($request->status == 1) {
            if ($projectUser) {
                $projectUser->update([
                    'active' => true,
                    'accepted' => true,
                ]);
            }
        }

        return ProjectUser::where('user_id', Auth::id())->where('accepted', false)->with('project')->get();
    }

    public function update(ProjectUser $projectUser, Request $request)
    {
        $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
        if ($request->create) {
            array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
        }

        if ($request->edit) {
            array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
            array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
        }
        $projectUser->permissions()->detach();

        $projectUser->permissions()->attach($permissionsToAdd);

        return response()->json([
            'success' => true,
            'message' => 'The user permissions have been updated',
            'data' => new ProjectUserResource($projectUser),
        ], 201);
    }

    public function destroy(ProjectUser $projectUser)
    {
        $projectUser->delete();

        return response()->json(null, 204);
    }
}
