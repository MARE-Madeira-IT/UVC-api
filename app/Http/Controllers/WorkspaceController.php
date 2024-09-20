<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\QueryFilters\WorkspaceFilters;
use App\Http\Requests\WorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Permission;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkspaceController extends Controller
{
    public function self(WorkspaceFilters $filters)
    {
        $user = Auth::user();
        return WorkspaceResource::collection($user->workspaces()->filterBy($filters)->wherePivot('active', true)->paginate(10));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WorkspaceFilters $filters)
    {
        return WorkspaceResource::collection(Workspace::filterBy($filters)->paginate(10));
    }

    public function selector(WorkspaceFilters $filters)
    {
        return WorkspaceResource::collection(Workspace::filterBy($filters)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkspaceRequest $request)
    {
        $validator = $request->validated();

        $workspace = Workspace::create($validator);

        $workspaceUser = WorkspaceUser::create([
            'workspace_id' => $workspace->id,
            'user_id' => Auth::id(),
            'active' => 1
        ]);

        $workspaceUser->permissions()->attach(Permission::all()->pluck('id')->toArray());

        return new WorkspaceResource($workspace);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Workspace  $workspace
     * @return \Illuminate\Http\Response
     */
    public function show(Workspace $workspace)
    {
        return new WorkspaceResource($workspace);
    }

    private function hasPermission($workspace, $permission)
    {
        $user_id = Auth::id();
        $workspaceUser = WorkspaceUser::where('user_id', $user_id)
            ->where('workspace_id', $workspace->id)
            ->where('active', true)
            ->first();

        if (is_null($workspaceUser)) {
            return false;
        }

        return $workspaceUser->permissions()->where('name', $permission)->count() > 0;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Workspace  $workspace
     * @return \Illuminate\Http\Response
     */
    public function update(WorkspaceRequest $request, Workspace $workspace)
    {
        if (!self::hasPermission($workspace, 'edit')) {
            return response()->json(["You don't have access to edit the workspace: \"{$workspace->name}\""], 403);
        }

        DB::beginTransaction();

        $validator = $request->validated();

        $workspace->update($validator);


        foreach ($request["users"] as $user) {

            $existingUser = User::where('email', $user["email"])->first();
            if (is_null($existingUser)) {
                return response()->json(
                    ["message" => "The user with email " . $user["email"] . " doesn't exist"],
                    409
                );
            }

            $workspaceUser = WorkspaceUser::updateOrCreate(
                [
                    'user_id' => $existingUser["id"],
                    "workspace_id" => $workspace->id
                ],
                [
                    "workspace_id" => $workspace->id,
                    "user_id" => $existingUser->id
                ]
            );

            $workspaceUser->permissions()->detach();

            $permissionsToAdd = [Permission::where('name', 'show')->first()->id];
            if (array_key_exists("create", $user) && $user["create"]) {
                array_push($permissionsToAdd, Permission::where('name', 'create')->first()->id);
            }

            if (array_key_exists("edit", $user) && $user["edit"]) {
                array_push($permissionsToAdd, Permission::where('name', 'edit')->first()->id);
                array_push($permissionsToAdd, Permission::where('name', 'delete')->first()->id);
            }

            $workspaceUser->permissions()->attach($permissionsToAdd);
        }

        DB::commit();

        return new WorkspaceResource($workspace);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Workspace  $workspace
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workspace $workspace)
    {
        $workspace->delete();

        return response()->json(null, 204);
    }

    public function getWorkspacePermissions(Workspace $workspace)
    {
        $user = Auth::user();

        $workspaceUser = WorkspaceUser::where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->where('active', true)
            ->first();

        if (is_null($workspaceUser)) {
            return response()->json([
                "message" => 'You don\'t have access to this workspace',
                'permissions' => []
            ], 403);
        }

        $permissions = $workspaceUser->permissions()->get()->pluck("name");

        return response()->json(["message" => 'Workspace is private', 'permissions' => $permissions], 200);
    }
}
