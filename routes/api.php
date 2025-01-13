<?php

use App\Http\Controllers\AcceptMemberToSurveyProgramInvokable;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FetchSurveyProgramInvitesInvokable;
use App\Http\Controllers\FetchSurveyProgramStatisticsInvokable;
use App\Http\Controllers\FetchReportCoordinatesInvokable;
use App\Http\Controllers\FetchSelfSurveyProgramsInvokable;
use App\Http\Controllers\InviteMemberToSurveyProgramInvokable;
use App\Http\Controllers\BenthicController;
use App\Http\Controllers\DepthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FetchSelfProjectsInvokable;
use App\Http\Controllers\FetchSelfWorkspacesInvokable;
use App\Http\Controllers\SurveyProgramFunctionController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\MotileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\SurveyProgramController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SizeCategoryController;
use App\Http\Controllers\SubstrateController;
use App\Http\Controllers\SurveyProgramUserController;
use App\Http\Controllers\TaxaCategoryController;
use App\Http\Controllers\TaxaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceUserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [AuthController::class, "logout"]);
    Route::get('me', [AuthController::class, "me"]);

    Route::get('invites', [UserController::class, "getUserInvites"]);

    Route::get('self-survey-programs', [SurveyProgramController::class, 'self']);
    Route::get('self-projects', [ProjectController::class, 'self']);
    Route::get('self-workspaces', [WorkspaceController::class, 'self']);

    Route::get('survey-program-statistics/{surveyProgram}', FetchSurveyProgramStatisticsInvokable::class);

    Route::get('surveyProgramUsers', [SurveyProgramUserController::class, "index"]);
    Route::post('surveyProgramUsers/invite-member', [SurveyProgramUserController::class, "store"]);
    Route::put('surveyProgramUsers/{surveyProgramUser}', [SurveyProgramUserController::class, "update"]);
    Route::delete('surveyProgramUsers/{surveyProgramUser}', [SurveyProgramUserController::class, "destroy"]);
    Route::get('surveyProgramUsers/invites', [SurveyProgramUserController::class, "getUserInvites"]);
    Route::put('surveyProgramUsers/{surveyProgramUser}/accept', [SurveyProgramUserController::class, "acceptInvite"]);

    Route::get('projectUsers', [ProjectUserController::class, "index"]);
    Route::post('projectUsers/invite-member', [ProjectUserController::class, "store"]);
    Route::put('projectUsers/{projectUser}', [ProjectUserController::class, "update"]);
    Route::delete('projectUsers/{projectUser}', [ProjectUserController::class, "destroy"]);
    Route::get('projectUsers/invites', [ProjectUserController::class, "getUserInvites"]);
    Route::put('projectUsers/{projectUser}/accept', [ProjectUserController::class, "acceptInvite"]);

    Route::get('workspaceUsers', [WorkspaceUserController::class, "index"]);
    Route::post('workspaceUsers/invite-member', [WorkspaceUserController::class, "store"]);
    Route::put('workspaceUsers/{workspaceUser}', [WorkspaceUserController::class, "update"]);
    Route::delete('workspaceUsers/{workspaceUser}', [WorkspaceUserController::class, "destroy"]);
    Route::get('workspaceUsers/invites', [WorkspaceUserController::class, "getUserInvites"]);
    Route::put('workspaceUsers/{workspaceUser}/accept', [WorkspaceUserController::class, "acceptInvite"]);

    Route::get('localities', [LocalityController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('localities', [LocalityController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('localities/{locality}', [LocalityController::class, "show"])->middleware('survey_program_permission:show|locality');
    Route::put('localities/{locality}', [LocalityController::class, "update"])->middleware('survey_program_permission:edit|locality');
    Route::delete('localities/{locality}', [LocalityController::class, "destroy"])->middleware('survey_program_permission:delete|locality');


    Route::get('taxas', [TaxaController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('taxas', [TaxaController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('taxas/{taxa}', [TaxaController::class, "show"])->middleware('survey_program_permission:show|taxa');
    Route::put('taxas/{taxa}', [TaxaController::class, "update"])->middleware('survey_program_permission:edit|taxa');
    Route::delete('taxas/{taxa}', [TaxaController::class, "destroy"])->middleware('survey_program_permission:delete|taxa');


    Route::post('taxas/photo/{taxa}', [TaxaController::class, "uploadPhoto"]);
    Route::put('taxas/toggle-validation/{taxa}', [TaxaController::class, "toggleValidation"]);

    Route::get('indicators', [IndicatorController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('indicators', [IndicatorController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('indicators/{indicator}', [IndicatorController::class, "show"])->middleware('survey_program_permission:show|indicator');
    Route::put('indicators/{indicator}', [IndicatorController::class, "update"])->middleware('survey_program_permission:edit|indicator');
    Route::delete('indicators/{indicator}', [IndicatorController::class, "destroy"])->middleware('survey_program_permission:delete|indicator');


    Route::get('reports', [ReportController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('reports', [ReportController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('reports/{report}', [ReportController::class, "show"])->middleware('survey_program_permission:show|report');
    Route::put('reports/{report}', [ReportController::class, "update"])->middleware('survey_program_permission:edit|report');
    Route::delete('reports/{report}', [ReportController::class, "destroy"])->middleware('survey_program_permission:delete|report');

    Route::get('exports', [ExportController::class, "index"]);
    Route::get('exports', [ExportController::class, "index"]);
    Route::post('exports', [ExportController::class, "store"]);
    Route::get('exports/{export}', [ExportController::class, "show"]);
    Route::delete('exports/{export}', [ExportController::class, "destroy"]);


    Route::get('surveyPrograms', [SurveyProgramController::class, "index"]);
    Route::post('surveyPrograms', [SurveyProgramController::class, "store"]);
    Route::get('surveyPrograms/{surveyProgram}', [SurveyProgramController::class, "show"]);
    Route::put('surveyPrograms/{surveyProgram}', [SurveyProgramController::class, "update"]);
    Route::put('surveyPrograms/{surveyProgram}/users', [SurveyProgramController::class, "updateUsers"]);
    Route::delete('surveyPrograms/{surveyProgram}', [SurveyProgramController::class, "destroy"]);
    Route::get('surveyPrograms/{surveyProgram}/permissions', [SurveyProgramController::class, "getSurveyProgramPermissions"]);
    Route::get('surveyPrograms/importStatus/{id}', [SurveyProgramController::class, 'importStatus']);
    Route::get("surveyPrograms/{surveyProgram}/export", [SurveyProgramController::class, "xlsxExport"]);


    Route::get('projects', [ProjectController::class, "index"]);
    Route::post('projects', [ProjectController::class, "store"]);
    Route::get('projects/{project}', [ProjectController::class, "show"]);
    Route::put('projects/{project}', [ProjectController::class, "update"]);
    Route::put('projects/{project}/users', [ProjectController::class, "updateUsers"]);
    Route::delete('projects/{project}', [ProjectController::class, "destroy"]);
    Route::get('projects/{project}/permissions', [ProjectController::class, "getProjectPermissions"]);

    Route::get('workspaces', [WorkspaceController::class, "index"]);
    Route::post('workspaces', [WorkspaceController::class, "store"]);
    Route::get('workspaces/{workspace}', [WorkspaceController::class, "show"]);
    Route::put('workspaces/{workspace}', [WorkspaceController::class, "update"]);
    Route::put('workspaces/{workspace}/users', [WorkspaceController::class, "updateUsers"]);
    Route::delete('workspaces/{workspace}', [WorkspaceController::class, "destroy"]);
    Route::get('workspaces/{workspace}/permissions', [WorkspaceController::class, "getWorkspacePermissions"]);

    Route::get('taxa_categories', [TaxaCategoryController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('taxa_categories', [TaxaCategoryController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('taxa_categories/{taxaCategory}', [TaxaCategoryController::class, "show"])->middleware('survey_program_permission:show|taxaCategory');
    Route::put('taxa_categories/{taxaCategory}', [TaxaCategoryController::class, "update"])->middleware('survey_program_permission:edit|taxaCategory');
    Route::delete('taxa_categories/{taxaCategory}', [TaxaCategoryController::class, "destroy"])->middleware('survey_program_permission:delete|taxaCategory');


    Route::get('benthics', [BenthicController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('benthics', [BenthicController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('benthics/{report}', [BenthicController::class, "show"])->middleware('survey_program_permission:show|report');
    Route::put('benthics/{report}', [BenthicController::class, "update"])->middleware('survey_program_permission:edit|report');
    Route::delete('benthics/{report}', [BenthicController::class, "destroy"])->middleware('survey_program_permission:delete|report');


    Route::get('depths', [DepthController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('depths', [DepthController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('depths/{depth}', [DepthController::class, "show"])->middleware('survey_program_permission:show|depth');
    Route::put('depths/{depth}', [DepthController::class, "update"])->middleware('survey_program_permission:edit|depth');
    Route::delete('depths/{depth}', [DepthController::class, "destroy"])->middleware('survey_program_permission:delete|depth');


    Route::get('substrates', [SubstrateController::class, "index"])->middleware('survey_program_permission:show');


    Route::get('functions', [SurveyProgramFunctionController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('functions', [SurveyProgramFunctionController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('functions/{function}', [SurveyProgramFunctionController::class, "show"])->middleware('survey_program_permission:show|function');
    Route::put('functions/{function}', [SurveyProgramFunctionController::class, "update"])->middleware('survey_program_permission:edit|function');
    Route::delete('functions/{function}', [SurveyProgramFunctionController::class, "destroy"])->middleware('survey_program_permission:delete|function');


    Route::get('motiles', [MotileController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('motiles', [MotileController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('motiles/{reportMotile}', [MotileController::class, "show"])->middleware('survey_program_permission:show|reportMotile');
    Route::put('motiles/{reportMotile}', [MotileController::class, "update"])->middleware('survey_program_permission:edit|reportMotile');
    Route::delete('motiles/{reportMotile}', [MotileController::class, "destroy"])->middleware('survey_program_permission:delete|reportMotile');


    Route::prefix('selector')->group(function () {
        Route::get('indicators', [IndicatorController::class, "selector"]);
        Route::get('localities', [LocalityController::class, "selector"]);
        Route::get('taxa_categories', [TaxaCategoryController::class, "selector"]);
        Route::get('size_categories', [SizeCategoryController::class, "selector"]);
        Route::get('reports', [ReportController::class, "selector"]);
        Route::get('substrates', [SubstrateController::class, "selector"]);
        Route::get('depths', [DepthController::class, "selector"]);
        Route::get('functions', [SurveyProgramFunctionController::class, "selector"]);
        Route::get('report-coordinates', FetchReportCoordinatesInvokable::class);
        Route::get('taxas', [TaxaController::class, "selector"]);
        Route::get("workspaces", [WorkspaceController::class, "selector"]);
    });
});
