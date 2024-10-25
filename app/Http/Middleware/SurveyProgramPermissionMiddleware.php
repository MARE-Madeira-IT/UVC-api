<?php

namespace App\Http\Middleware;

use App\Models\SurveyProgram;
use App\Models\SurveyProgramUser;
use Closure;
use Illuminate\Support\Facades\Auth;

class SurveyProgramPermissionMiddleware
{

    private function checkUserPermissionOnSurveyProgram($user_id, $survey_program_id, $permission)
    {
        $mareSurveyProgramUser = SurveyProgramUser::where('user_id', $user_id)
            ->where('survey_program_id', $survey_program_id)
            ->where('active', true)
            ->first();

        if (is_null($mareSurveyProgramUser)) {
            return false;
        }

        return $mareSurveyProgramUser->permissions()->where('name', $permission)->count() > 0;
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
        $surveyProgram = $request->survey_program;

        if (is_null($surveyProgram)) {
            $surveyProgram = $request->survey_program_id;
        }

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
                $surveyProgramName = SurveyProgram::findOrFail($surveyProgram)->name;
                return response()->json(["You don't have access to {$permission} on the survey program: \"{$surveyProgramName}\""], 403);
            }
        }


        return $next($request);
    }
}
