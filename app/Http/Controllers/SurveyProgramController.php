<?php

namespace App\Http\Controllers;

use App\Exports\SurveyProgramExport;
use App\Http\Controllers\Controller;
use App\Http\QueryFilters\SurveyProgramFilters;
use App\Http\Requests\MemberChangeRequest;
use App\Http\Requests\SurveyProgramRequest;
use App\Http\Resources\SurveyProgramResource;
use App\Imports\SurveyProgramImport;
use App\Models\Permission;
use App\Models\SurveyProgram;
use App\Models\SurveyProgramUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SurveyProgramController extends Controller
{

    private function hasPermission($surveyProgram, $permission)
    {
        $user_id = Auth::id();
        $surveyProgramUser = SurveyProgramUser::where('user_id', $user_id)
            ->where('survey_program_id', $surveyProgram->id)
            ->where('active', true)
            ->first();

        if (is_null($surveyProgramUser)) {
            return false;
        }

        return $surveyProgramUser->permissions()->where('name', $permission)->count() > 0;
    }

    public function self(SurveyProgramFilters $filters)
    {
        $user = Auth::user();
        return SurveyProgramResource::collection($user->surveyPrograms()->filterBy($filters)->wherePivot('active', true)->paginate(10));
    }

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

        DB::beginTransaction();
        $surveyProgram = SurveyProgram::create([...$validator, "uploading" => true]);

        $surveyProgramUser = SurveyProgramUser::create([
            'survey_program_id' => $surveyProgram->id,
            'user_id' => Auth::id(),
            'active' => 1,
            'accepted' => 1,
        ]);

        $surveyProgramUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

        if ($request->file("file")) {
            cache()->forever("start_date_{$surveyProgram->id}", now());
            Excel::queueImport(new SurveyProgramImport($surveyProgram), $request->file("file"), null, \Maatwebsite\Excel\Excel::XLSX);
        }
        DB::commit();
        return new SurveyProgramResource($surveyProgram);
    }

    public function importStatus($id)
    {
        return response()->json([
            "DIVE_SITE_METADATA" => [
                "current_row" => cache("current_row_DIVE_SITE_METADATA_$id"),
                "total_rows" => cache("total_rows_DIVE_SITE_METADATA_$id"),
            ],
            "BENTHIC_TAXAS" => [
                "current_row" => cache("current_row_BENTHIC_TAXAS_$id"),
                "total_rows" => cache("total_rows_BENTHIC_TAXAS_$id"),
            ],
            "MOTILE_TAXAS" => [
                "current_row" => cache("current_row_MOTILE_TAXAS_$id"),
                "total_rows" => cache("total_rows_MOTILE_TAXAS_$id"),
            ],
            "BENTHIC_DB" => [
                "current_row" => cache("current_row_BENTHIC_DB_$id"),
                "total_rows" => cache("total_rows_BENTHIC_DB_$id"),
            ],
            "MOTILE_DB" => [
                "current_row" => cache("current_row_MOTILE_DB_$id"),
                "total_rows" => cache("total_rows_MOTILE_DB_$id"),
            ],
            "errors" => cache("errors_DIVE_SITE_METADATA_{$id}"),
            "start_date" => cache("start_date_{$id}"),
        ], 200);
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
        if (!self::hasPermission($surveyProgram, 'admin')) {
            return response()->json(["You don't have access to edit the survey program: \"{$surveyProgram->name}\""], 403);
        }

        $validator = $request->validated();

        $surveyProgram->update($validator);
        return new SurveyProgramResource($surveyProgram);
    }

    public function updateUsers(MemberChangeRequest $request, SurveyProgram $surveyProgram)
    {
        if (!self::hasPermission($surveyProgram, 'admin')) {
            return response()->json(["You don't have access to edit the survey program: \"{$surveyProgram->name}\""], 403);
        }

        $validator = $request->validated();

        $currentUsersIds = $surveyProgram->surveyProgramUsers->pluck("id")->toArray();
        $usersToKeep = array();


        foreach ($validator["users"] as $user) {

            $existingUser = User::where('email', $user["email"])->first();
            if (is_null($existingUser)) {
                return response()->json(
                    ["message" => "The user with email " . $user["email"] . " doesn't exist"],
                    409
                );
            }

            $surveyProgramUser = SurveyProgramUser::updateOrCreate(
                [
                    'user_id' => $existingUser["id"],
                    "survey_program_id" => $surveyProgram->id
                ],
                [
                    "survey_program_id" => $surveyProgram->id,
                    "user_id" => $existingUser->id
                ]
            );
            array_push($usersToKeep, $surveyProgramUser->id);

            $surveyProgramUser->permissions()->detach();

            $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
            if (array_key_exists("create", $user) && $user["create"]) {
                array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
            }
            if (array_key_exists("admin", $user) && $user["admin"]) {
                array_push($permissionsToAdd, Permission::where('name', 'admin')->first()->id);
            }

            if (array_key_exists("edit", $user) && $user["edit"]) {
                array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
                array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
            }

            $surveyProgramUser->permissions()->attach($permissionsToAdd);
        }

        $idsToRemove = array_diff($currentUsersIds, $usersToKeep);

        SurveyProgramUser::whereIn('id', $idsToRemove)->delete();

        return new SurveyProgramResource($surveyProgram->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SurveyProgram  $mareSurveyProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(SurveyProgram $surveyProgram)
    {
        if (!self::hasPermission($surveyProgram, 'admin')) {
            return response()->json(["You don't have access to edit the survey program: \"{$surveyProgram->name}\""], 403);
        }

        $surveyProgram->surveyProgramUsers()->delete();
        $surveyProgram->delete();

        return response()->json(null, 204);
    }

    public function getSurveyProgramPermissions(SurveyProgram $surveyProgram)
    {
        $user = Auth::user();

        $mareSurveyProgramUser = SurveyProgramUser::where('user_id', $user->id)
            ->where('survey_program_id', $surveyProgram->id)
            ->where('active', true)
            ->first();

        if (is_null($mareSurveyProgramUser)) {
            return response()->json([
                "message" => 'You don\'t have access to this survey program',
                'permissions' => []
            ], 403);
        }

        $permissions = $mareSurveyProgramUser->permissions()->get()->pluck("name");

        return response()->json(["message" => 'SurveyProgram is private', 'permissions' => $permissions], 200);
    }

    public function xlsxExport(SurveyProgram $surveyProgram)
    {
        return (new SurveyProgramExport($surveyProgram))->download($surveyProgram->name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, []);
    }
}
