<?php

namespace App\Http\Controllers;

use App\Http\QueryFilters\SurveyProgramUserFilters;
use App\Http\Resources\SurveyProgramUserResource;
use App\Models\Permission;
use App\Models\SurveyProgramUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyProgramUserController extends Controller
{
    public function index(SurveyProgramUserFilters $filters)
    {
        return SurveyProgramUserResource::collection(
            SurveyProgramUser::filterBy($filters)->paginate(10)
        );
    }

    public function getUserInvites()
    {
        return SurveyProgramUserResource::collection(
            SurveyProgramUser::where('user_id', Auth::id())->where('accepted', false)->get()
        );
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $surveyProgramUser = SurveyProgramUser::create([
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

            $surveyProgramUser->permissions()->attach($permissionsToAdd);

            return response()->json([
                'data' => new SurveyProgramUserResource(
                    $surveyProgramUser
                )
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'There is no registered user with that email',
            ], 422);
        }
    }

    public function acceptInvite(SurveyProgramUser $surveyProgramUser, Request $request)
    {
        if ($request->status == 1) {
            if ($surveyProgramUser) {
                $surveyProgramUser->update([
                    'active' => true,
                    'accepted' => true,
                ]);
            }
        }

        return SurveyProgramUser::where('user_id', Auth::id())->where('accepted', false)->with('surveyProgram')->get();
    }

    public function update(SurveyProgramUser $surveyProgramUser, Request $request)
    {
        $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
        if ($request->create) {
            array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
        }

        if ($request->edit) {
            array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
            array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
        }
        $surveyProgramUser->permissions()->detach();

        $surveyProgramUser->permissions()->attach($permissionsToAdd);

        return response()->json([
            'success' => true,
            'message' => 'The user permissions have been updated',
            'data' => new SurveyProgramUserResource($surveyProgramUser),
        ], 201);
    }

    public function destroy(SurveyProgramUser $surveyProgramUser)
    {
        $surveyProgramUser->delete();

        return response()->json(null, 204);
    }
}
