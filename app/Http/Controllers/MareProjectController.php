<?php

namespace App\Http\Controllers;

use App\Exports\MareProjectExport;
use App\Http\Controllers\Controller;
use App\Http\QueryFilters\MareProjectFilters;
use App\Http\QueryFilters\UserFilters;
use App\Http\Requests\MareProjectRequest;
use App\Http\Resources\MareProjectResource;
use App\Http\Resources\MareUserResource;
use App\Models\MareFunction;
use App\Models\MarePermission;
use App\Models\MareProject;
use App\Models\MareProjectHasUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MareProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MareProjectFilters $filters)
    {
        return MareProjectResource::collection(MareProject::filterBy($filters)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MareProjectRequest $request)
    {
        $validator = $request->validated();

        $project = MareProject::create($validator);

        $mareProjectHasUser = MareProjectHasUser::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'active' => 1
        ]);

        $mareProjectHasUser->permissions()->attach(MarePermission::all()->pluck('id')->toArray());

        $teamFunctions = [
            ['name' => 'fish', 'project_id' => $project->id],
            ['name' => 'cryptic', 'project_id' => $project->id],
            ['name' => 'macroinv', 'project_id' => $project->id],
            ['name' => 'dom_urchin', 'project_id' => $project->id],
            ['name' => 'benthic_t', 'project_id' => $project->id],
            ['name' => 'photo_q', 'project_id' => $project->id],
        ];

        foreach ($teamFunctions as $teamFunction) {
            MareFunction::create($teamFunction);
        }

        return new MareProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MareProject  $mareProject
     * @return \Illuminate\Http\Response
     */
    public function show(MareProject $project)
    {
        return new MareProjectResource($project);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MareProject  $mareProject
     * @return \Illuminate\Http\Response
     */
    public function update(MareProjectRequest $request, MareProject $project)
    {
        $validator = $request->validated();

        $project->update($validator);
        return new MareProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MareProject  $mareProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(MareProject $project)
    {
        $project->delete();

        return response()->json(null, 204);
    }

    public function getProjectPermissions(MareProject $project)
    {
        $user = Auth::user();

        $mareProjectHasUser = MareProjectHasUser::where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->where('active', true)
            ->first();

        if (is_null($mareProjectHasUser)) {
            return response()->json([
                "message" => 'You don\'t have access to this project',
                'permissions' => []
            ], 403);
        }

        $permissions = $mareProjectHasUser->permissions()->get()->pluck("name");

        return response()->json(["message" => 'Project is private', 'permissions' => $permissions], 200);
    }


    public function getMembers(Request $request)
    {
        $filters = UserFilters::hydrate($request->query());
        $userIds = User::filterBy($filters)->paginate(10)->pluck("id");

        return MareUserResource::collection(MareProjectHasUser::where('project_id', $request->project)
            ->whereIn('user_id', $userIds)->get());
    }


    public function updateMember($project_id, $user_id, Request $request)
    {
        $projectHasUser = MareProjectHasUser::where([
            "project_id" => $project_id,
            "user_id" => $user_id,
        ])->first();

        $permissionsToAdd = [MarePermission::where('name', 'show')->first()->id];
        if ($request->create) {
            array_push($permissionsToAdd, MarePermission::where('name', 'create')->first()->id);
        }

        if ($request->edit) {
            array_push($permissionsToAdd, MarePermission::where('name', 'edit')->first()->id);
            array_push($permissionsToAdd, MarePermission::where('name', 'delete')->first()->id);
        }
        $projectHasUser->permissions()->detach();

        $projectHasUser->permissions()->attach($permissionsToAdd);

        return response()->json([
            'success' => true,
            'message' => 'The user permissions have been updated',
            'data' => new MareUserResource($projectHasUser),
        ], 201);
    }

    public function xlsxExport(MareProject $project)
    {
        return (new MareProjectExport($project))->download($project->name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, []);
    }
}
