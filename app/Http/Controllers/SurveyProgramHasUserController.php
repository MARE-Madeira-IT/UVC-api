<?php

namespace App\Http\Controllers;

use App\Http\QueryFilters\MemberFilters;
use App\Http\Resources\SurveyProgramUserResource;
use App\Models\Permission;
use App\Models\SurveyProgramHasUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyProgramHasUserController extends Controller
{
    public function index(MemberFilters $filters)
    {
        return SurveyProgramUserResource::collection(
            SurveyProgramHasUser::filterBy($filters)->paginate(10)
        );
    }

    public function getUserInvites()
    {
        return SurveyProgramUserResource::collection(
            SurveyProgramHasUser::where('user_id', Auth::id())->where('accepted', false)->get()
        );
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
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
                'data' => new SurveyProgramUserResource(
                    $surveyProgramHasUser
                )
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is no registered user with that email',
            ], 422);
        }
    }

    public function acceptInvite(SurveyProgramHasUser $surveyProgramHasUser, Request $request)
    {
        if ($request->status == 1) {
            if ($surveyProgramHasUser) {
                $surveyProgramHasUser->update([
                    'active' => true,
                    'accepted' => true,
                ]);
            }
        }

        return SurveyProgramHasUser::where('user_id', Auth::id())->where('accepted', false)->with('surveyProgram')->get();
    }

    public function update(SurveyProgramHasUser $surveyProgramHasUser, Request $request)
    {
        $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
        if ($request->create) {
            array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
        }

        if ($request->edit) {
            array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
            array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
        }
        $surveyProgramHasUser->permissions()->detach();

        $surveyProgramHasUser->permissions()->attach($permissionsToAdd);

        return response()->json([
            'success' => true,
            'message' => 'The user permissions have been updated',
            'data' => new SurveyProgramUserResource($surveyProgramHasUser),
        ], 201);
    }

    public function destroy(SurveyProgramHasUser $surveyProgramHasUser)
    {
        $surveyProgramHasUser->delete();

        return response()->json(null, 204);
    }
}
