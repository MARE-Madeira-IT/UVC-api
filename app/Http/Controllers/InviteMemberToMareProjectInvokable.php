<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MareInvite;
use App\Models\MarePermission;
use App\Models\MareProjectHasUser;
use App\Models\User;
use Illuminate\Http\Request;

class InviteMemberToMareProjectInvokable extends Controller
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
            MareInvite::create([
                "project_id" => $request->project_id,
                "user_id" => $user->id
            ]);

            $projectHasUser = MareProjectHasUser::create([
                "project_id" => $request->project_id,
                "user_id" => $user->id
            ]);

            $permissionsToAdd = [MarePermission::where('name', 'show')->first()->id];
            if ($request->create) {
                array_push($permissionsToAdd, MarePermission::where('name', 'create')->first()->id);
            }

            if ($request->edit) {
                array_push($permissionsToAdd, MarePermission::where('name', 'edit')->first()->id);
                array_push($permissionsToAdd, MarePermission::where('name', 'delete')->first()->id);
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
