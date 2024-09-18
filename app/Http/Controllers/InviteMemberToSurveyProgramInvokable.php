<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\Permission;
use App\Models\SurveyProgramHasUser;
use App\Models\User;
use Illuminate\Http\Request;

class InviteMemberToSurveyProgramInvokable extends Controller
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
                "survey_program_id" => $request->survey_program_id,
                "user_id" => $user->id
            ]);

            $surveyProgramHasUser = SurveyProgramHasUser::create([
                "survey_program_id" => $request->survey_program_id,
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

            $surveyProgramHasUser->permissions()->attach($permissionsToAdd);

            return response()->json([
                'success' => true,
                'message' => 'The user has been invited to the survey program',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is no registered user with that email',
            ], 422);
        }
    }
}
