<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\QueryFilters\ProjectFilters;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Permission;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function self(ProjectFilters $filters)
    {
        $user = Auth::user();
        return ProjectResource::collection($user->projects()->filterBy($filters)->wherePivot('active', true)->paginate(10));
    }
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

        $projectUser = ProjectUser::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'active' => 1
        ]);

        $projectUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
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
     * @param  \App\Project  $project
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
     * @param  \App\Project  $project
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

        $projectUser = ProjectUser::where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->where('active', true)
            ->first();

        if (is_null($projectUser)) {
            return response()->json([
                "message" => 'You don\'t have access to this project',
                'permissions' => []
            ], 403);
        }

        $permissions = $projectUser->permissions()->get()->pluck("name");

        return response()->json(["message" => 'Project is private', 'permissions' => $permissions], 200);
    }
}
