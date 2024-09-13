<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\Permission;
use App\Models\ProjectHasUser;
use App\Models\User;
use Illuminate\Http\Request;

class InviteMemberToProjectInvokable extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            Invite::create([
                "project_id" => $request->project_id,
                "user_id" => $user->id
            ]);

            $projectHasUser = ProjectHasUser::create([
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

            $projectHasUser->permissions()->attach($permissionsToAdd);

            return response()->json([
                'success' => true,
                'message' => 'The user has been invited to the project',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is no registered user with that email',
            ], 422);
        }
    }
}
