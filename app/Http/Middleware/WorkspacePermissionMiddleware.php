<?php

namespace App\Http\Middleware;

use App\Models\Workspace;
use App\Models\WorkspaceUser;
use Closure;
use Illuminate\Support\Facades\Auth;

class WorkspacePermissionMiddleware
{

    private function checkUserPermissionOnWorkspace($user_id, $workspace_id, $permission)
    {
        $workspaceUser = WorkspaceUser::where('user_id', $user_id)
            ->where('workspace_id', $workspace_id)
            ->where('active', true)
            ->first();

        if (is_null($workspaceUser)) {
            return false;
        }

        return $workspaceUser->permissions()->where('name', $permission)->count() > 0;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, string $permission)
    {
        $user = Auth::user();
        $workspace = $request->header('workspace');

        try {
            $workspaceArray = json_decode($workspace);

            foreach ($workspaceArray as $workspaceId) {
                if (!WorkspacePermissionMiddleware::checkUserPermissionOnWorkspace($user->id, $workspaceId, $permission)) {
                    $workspaceName = Workspace::findOrFail($workspaceId)->name;
                    return response()->json(["You don't have access to {$permission} on the workspace: \"{$workspaceName}\""], 403);
                }
            }
        } catch (\Throwable $th) {
            if (!WorkspacePermissionMiddleware::checkUserPermissionOnWorkspace($user->id, $workspaceArray, $permission)) {
                $workspaceName = Workspace::findOrFail($workspaceArray)->name;
                return response()->json(["You don't have access to {$permission} on the workspace: \"{$workspaceName}\""], 403);
            }
        }


        return $next($request);
    }
}
