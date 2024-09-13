<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\ProjectHasUser;
use Closure;
use Illuminate\Support\Facades\Auth;

class ProjectPermissionMiddleware
{

    private function checkUserPermissionOnProject($user_id, $project_id, $permission)
    {
        $mareProjectHasUser = ProjectHasUser::where('user_id', $user_id)
            ->where('project_id', $project_id)
            ->where('active', true)
            ->first();

        if (is_null($mareProjectHasUser)) {
            return false;
        }

        return $mareProjectHasUser->permissions()->where('name', $permission)->count() > 0;
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
        $project = $request->header('project');


        try {
            $projectArray = json_decode($project);

            foreach ($projectArray as $projectId) {
                if (!ProjectPermissionMiddleware::checkUserPermissionOnProject($user->id, $projectId, $permission)) {
                    $projectName = Project::findOrFail($projectId)->name;
                    return response()->json(["You don't have access to {$permission} on the project: \"{$projectName}\""], 403);
                }
            }
        } catch (\Throwable $th) {
            if (!ProjectPermissionMiddleware::checkUserPermissionOnProject($user->id, $projectArray, $permission)) {
                $projectName = Project::findOrFail($projectArray)->name;
                return response()->json(["You don't have access to {$permission} on the project: \"{$projectName}\""], 403);
            }
        }


        return $next($request);
    }
}
