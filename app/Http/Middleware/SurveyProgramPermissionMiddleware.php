<?php

namespace App\Http\Middleware;

use App\Models\SurveyProgram;
use App\Models\SurveyProgramHasUser;
use Closure;
use Illuminate\Support\Facades\Auth;

class SurveyProgramPermissionMiddleware
{

    private function checkUserPermissionOnSurveyProgram($user_id, $survey_program_id, $permission)
    {
        $mareSurveyProgramHasUser = SurveyProgramHasUser::where('user_id', $user_id)
            ->where('survey_program_id', $survey_program_id)
            ->where('active', true)
            ->first();

        if (is_null($mareSurveyProgramHasUser)) {
            return false;
        }

        return $mareSurveyProgramHasUser->permissions()->where('name', $permission)->count() > 0;
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
        $surveyProgram = $request->header('survey_program');


        try {
            $surveyProgramArray = json_decode($surveyProgram);

            foreach ($surveyProgramArray as $surveyProgramId) {
                if (!SurveyProgramPermissionMiddleware::checkUserPermissionOnSurveyProgram($user->id, $surveyProgramId, $permission)) {
                    $surveyProgramName = SurveyProgram::findOrFail($surveyProgramId)->name;
                    return response()->json(["You don't have access to {$permission} on the survey program: \"{$surveyProgramName}\""], 403);
                }
            }
        } catch (\Throwable $th) {
            if (!SurveyProgramPermissionMiddleware::checkUserPermissionOnSurveyProgram($user->id, $surveyProgramArray, $permission)) {
                $surveyProgramName = SurveyProgram::findOrFail($surveyProgramArray)->name;
                return response()->json(["You don't have access to {$permission} on the survey program: \"{$surveyProgramName}\""], 403);
            }
        }


        return $next($request);
    }
}
