<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\QueryFilters\ProjectFilters;
use App\Http\Requests\MemberChangeRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Permission;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'active' => 1,
            'accepted' => 1,
        ]);

        $projectUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

        $waveAdminUser = ProjectUser::updateOrCreate([
            'project_id' => $project->id,
            'user_id' => User::where("email", "admin@admin.wave")->first()->id,
        ], [
            'project_id' => $project->id,
            'user_id' => User::where("email", "admin@admin.wave")->first()->id,
            'active' => 1,
            'accepted' => 1,
        ]);

        $waveAdminUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

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

    private function hasPermission($project, $permission)
    {
        $user_id = Auth::id();
        $projectUser = ProjectUser::where('user_id', $user_id)
            ->where('project_id', $project->id)
            ->where('active', true)
            ->first();

        if (is_null($projectUser)) {
            return false;
        }

        return $projectUser->permissions()->where('name', $permission)->count() > 0;
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
        if (!self::hasPermission($project, 'admin')) {
            return response()->json(["You don't have access to edit the project: \"{$project->name}\""], 403);
        }
        DB::beginTransaction();

        $validator = $request->validated();

        $project->update($validator);

        DB::commit();
        return new ProjectResource($project);
    }

    public function updateUsers(MemberChangeRequest $request, Project $project)
    {
        if (!self::hasPermission($project, 'admin')) {
            return response()->json(["You don't have access to edit the project: \"{$project->name}\""], 403);
        }

        $validator = $request->validated();

        $currentUsersIds = $project->projectUsers->pluck("id")->toArray();
        $usersToKeep = array();


        foreach ($validator["users"] as $user) {

            $existingUser = User::where('email', $user["email"])->first();
            if (is_null($existingUser)) {
                return response()->json(
                    ["message" => "The user with email " . $user["email"] . " doesn't exist"],
                    409
                );
            }

            $projectUser = ProjectUser::updateOrCreate(
                [
                    'user_id' => $existingUser["id"],
                    "project_id" => $project->id
                ],
                [
                    "project_id" => $project->id,
                    "user_id" => $existingUser->id
                ]
            );
            array_push($usersToKeep, $projectUser->id);

            $projectUser->permissions()->detach();

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

            $projectUser->permissions()->attach($permissionsToAdd);
        }

        $idsToRemove = array_diff($currentUsersIds, $usersToKeep);

        ProjectUser::whereIn('id', $idsToRemove)->delete();

        return new ProjectResource($project->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if (!self::hasPermission($project, 'admin')) {
            return response()->json(["You don't have access to edit the project: \"{$project->name}\""], 403);
        }

        $project->projectUsers()->delete();
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
