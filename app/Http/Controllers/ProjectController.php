<?php

namespace App\Http\Controllers;

use App\Exports\ProjectExport;
use App\Http\Controllers\Controller;
use App\Http\QueryFilters\ProjectFilters;
use App\Http\QueryFilters\UserFilters;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;
use App\Models\ProjectFunction;
use App\Models\Permission;
use App\Models\Project;
use App\Models\ProjectHasUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectFilters $filters)
    {
        return ProjectResource::collection(Project::filterBy($filters)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $validator = $request->validated();

        $project = Project::create($validator);

        $mareProjectHasUser = ProjectHasUser::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'active' => 1
        ]);

        $mareProjectHasUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

        $teamFunctions = [
            ['name' => 'fish', 'project_id' => $project->id],
            ['name' => 'cryptic', 'project_id' => $project->id],
            ['name' => 'macroinv', 'project_id' => $project->id],
            ['name' => 'dom_urchin', 'project_id' => $project->id],
            ['name' => 'benthic_t', 'project_id' => $project->id],
            ['name' => 'photo_q', 'project_id' => $project->id],
        ];

        foreach ($teamFunctions as $teamFunction) {
            ProjectFunction::create($teamFunction);
        }

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $mareProject
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $mareProject
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $validator = $request->validated();

        $project->update($validator);
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $mareProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(null, 204);
    }

    public function getProjectPermissions(Project $project)
    {
        $user = Auth::user();

        $mareProjectHasUser = ProjectHasUser::where('user_id', $user->id)
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

        return UserResource::collection(ProjectHasUser::where('project_id', $request->project)
            ->whereIn('user_id', $userIds)->get());
    }


    public function updateMember($project_id, $user_id, Request $request)
    {
        $projectHasUser = ProjectHasUser::where([
            "project_id" => $project_id,
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
        $projectHasUser->permissions()->detach();

        $projectHasUser->permissions()->attach($permissionsToAdd);

        return response()->json([
            'success' => true,
            'message' => 'The user permissions have been updated',
            'data' => new UserResource($projectHasUser),
        ], 201);
    }

    public function xlsxExport(Project $project)
    {
        return (new ProjectExport($project))->download($project->name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX, []);
    }
}
