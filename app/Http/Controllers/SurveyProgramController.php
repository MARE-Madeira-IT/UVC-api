<?php

namespace App\Http\Controllers;

use App\Exports\SurveyProgramExport;
use App\Http\Controllers\Controller;
use App\Http\QueryFilters\SurveyProgramFilters;
use App\Http\QueryFilters\UserFilters;
use App\Http\Requests\SurveyProgramRequest;
use App\Http\Resources\SurveyProgramResource;
use App\Http\Resources\SurveyProgramUserResource;
use App\Http\Resources\UserResource;
use App\Models\SurveyProgramFunction;
use App\Models\Permission;
use App\Models\SurveyProgram;
use App\Models\SurveyProgramHasUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SurveyProgramFilters $filters)
    {
        return SurveyProgramResource::collection(SurveyProgram::filterBy($filters)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SurveyProgramRequest $request)
    {
        $validator = $request->validated();

        $surveyProgram = SurveyProgram::create($validator);

        $mareSurveyProgramHasUser = SurveyProgramHasUser::create([
            'survey_program_id' => $surveyProgram->id,
            'user_id' => Auth::id(),
            'active' => 1
        ]);

        $mareSurveyProgramHasUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

        return new SurveyProgramResource($surveyProgram);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SurveyProgram  $mareSurveyProgram
     * @return \Illuminate\Http\Response
     */
    public function show(SurveyProgram $surveyProgram)
    {
        return new SurveyProgramResource($surveyProgram);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SurveyProgram  $mareSurveyProgram
     * @return \Illuminate\Http\Response
     */
    public function update(SurveyProgramRequest $request, SurveyProgram $surveyProgram)
    {
        $validator = $request->validated();

        $surveyProgram->update($validator);
        return new SurveyProgramResource($surveyProgram);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SurveyProgram  $mareSurveyProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(SurveyProgram $surveyProgram)
    {
        $surveyProgram->delete();

        return response()->json(null, 204);
    }

    public function getSurveyProgramPermissions(SurveyProgram $surveyProgram)
    {
        $user = Auth::user();

        $mareSurveyProgramHasUser = SurveyProgramHasUser::where('user_id', $user->id)
            ->where('survey_program_id', $surveyProgram->id)
            ->where('active', true)
            ->first();

        if (is_null($mareSurveyProgramHasUser)) {
            return response()->json([
                "message" => 'You don\'t have access to this survey program',
                'permissions' => []
            ], 403);
        }

        $permissions = $mareSurveyProgramHasUser->permissions()->get()->pluck("name");

        return response()->json(["message" => 'SurveyProgram is private', 'permissions' => $permissions], 200);
    }


    public function getMembers(Request $request)
    {
        $filters = UserFilters::hydrate($request->query());
        $userIds = User::filterBy($filters)->paginate(10)->pluck("id");

        return SurveyProgramUserResource::collection(SurveyProgramHasUser::where('survey_program_id', $request->survey_program)
            ->whereIn('user_id', $userIds)->get());
    }


    public function updateMember($survey_program_id, $user_id, Request $request)
    {
        $surveyProgramHasUser = SurveyProgramHasUser::where([
            "survey_program_id" => $survey_program_id,
            "user_id" => $user_id,
        ])->first();

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

    public function xlsxExport(SurveyProgram $surveyProgram)
    {
        return (new SurveyProgramExport($surveyProgram))->download($surveyProgram->name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, []);
    }
}
