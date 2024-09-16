<?php

use App\Http\Controllers\AcceptMemberToProjectInvokable;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FetchProjectInvitesInvokable;
use App\Http\Controllers\FetchProjectStatisticsInvokable;
use App\Http\Controllers\FetchReportCoordinatesInvokable;
use App\Http\Controllers\FetchSelfProjectsInvokable;
use App\Http\Controllers\InviteMemberToProjectInvokable;
use App\Http\Controllers\BenthicController;
use App\Http\Controllers\DepthController;
use App\Http\Controllers\ProjectFunctionController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\MotileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SizeCategoryController;
use App\Http\Controllers\SubstrateController;
use App\Http\Controllers\TaxaCategoryController;
use App\Http\Controllers\TaxaController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [AuthController::class, "logout"]);
    Route::get('self-projects', FetchSelfProjectsInvokable::class);


    Route::get("export/{project}", [ProjectController::class, "xlsxExport"]);
    Route::get('permissions/{project}', [ProjectController::class, "getProjectPermissions"]);

    Route::get('project-statistics/{project}', FetchProjectStatisticsInvokable::class)->middleware('project_permission:show');

    Route::get('invites', FetchProjectInvitesInvokable::class);
    Route::post('invite-member', InviteMemberToProjectInvokable::class);
    Route::put('accept-member/{invite}', AcceptMemberToProjectInvokable::class);


    Route::get('localities', [LocalityController::class, "index"])->middleware('project_permission:show');
    Route::post('localities', [LocalityController::class, "store"])->middleware('project_permission:create');
    Route::get('localities/{locality}', [LocalityController::class, "show"])->middleware('project_permission:show');
    Route::put('localities/{locality}', [LocalityController::class, "update"])->middleware('project_permission:edit');
    Route::delete('localities/{locality}', [LocalityController::class, "destroy"])->middleware('project_permission:delete');


    Route::get('taxas', [TaxaController::class, "index"])->middleware('project_permission:show');
    Route::post('taxas', [TaxaController::class, "store"])->middleware('project_permission:create');
    Route::get('taxas/{taxa}', [TaxaController::class, "show"])->middleware('project_permission:show');
    Route::put('taxas/{taxa}', [TaxaController::class, "update"])->middleware('project_permission:edit');
    Route::delete('taxas/{taxa}', [TaxaController::class, "destroy"])->middleware('project_permission:delete');


    Route::post('taxas/photo/{taxa}', [TaxaController::class, "uploadPhoto"]);
    Route::put('taxas/toggle-validation/{taxa}', [TaxaController::class, "toggleValidation"]);

    Route::get('indicators', [IndicatorController::class, "index"])->middleware('project_permission:show');
    Route::post('indicators', [IndicatorController::class, "store"])->middleware('project_permission:create');
    Route::get('indicators/{indicator}', [IndicatorController::class, "show"])->middleware('project_permission:show');
    Route::put('indicators/{indicator}', [IndicatorController::class, "update"])->middleware('project_permission:edit');
    Route::delete('indicators/{indicator}', [IndicatorController::class, "destroy"])->middleware('project_permission:delete');


    Route::get('reports', [ReportController::class, "index"])->middleware('project_permission:show');
    Route::post('reports', [ReportController::class, "store"])->middleware('project_permission:create');
    Route::get('reports/{report}', [ReportController::class, "show"])->middleware('project_permission:show');
    Route::put('reports/{report}', [ReportController::class, "update"])->middleware('project_permission:edit');
    Route::delete('reports/{report}', [ReportController::class, "destroy"])->middleware('project_permission:delete');


    Route::get('projects/members', [ProjectController::class, "getMembers"]);
    Route::put('projects/{project_id}/members/{user_id}', [ProjectController::class, "updateMember"]);
    Route::get('projects', [ProjectController::class, "index"]);
    Route::post('projects', [ProjectController::class, "store"]);
    Route::get('projects/{project}', [ProjectController::class, "show"])->middleware('project_permission:show');
    Route::put('projects/{project}', [ProjectController::class, "update"])->middleware('project_permission:edit');
    Route::delete('projects/{project}', [ProjectController::class, "destroy"])->middleware('project_permission:delete');

    Route::get('taxa_categories', [TaxaCategoryController::class, "index"])->middleware('project_permission:show');

    Route::get('benthics', [BenthicController::class, "index"])->middleware('project_permission:show');
    Route::post('benthics', [BenthicController::class, "store"])->middleware('project_permission:create');
    Route::get('benthics/{id}', [BenthicController::class, "show"])->middleware('project_permission:show');
    Route::put('benthics/{benthic}', [BenthicController::class, "update"])->middleware('project_permission:edit');
    Route::delete('benthics/{benthic}', [BenthicController::class, "destroy"])->middleware('project_permission:delete');


    Route::get('depths', [DepthController::class, "index"])->middleware('project_permission:show');
    Route::post('depths', [DepthController::class, "store"])->middleware('project_permission:create');
    Route::get('depths/{depth}', [DepthController::class, "show"])->middleware('project_permission:show');
    Route::put('depths/{depth}', [DepthController::class, "update"])->middleware('project_permission:edit');
    Route::delete('depths/{depth}', [DepthController::class, "destroy"])->middleware('project_permission:delete');


    Route::get('substrates', [SubstrateController::class, "index"])->middleware('project_permission:show');


    Route::get('functions', [ProjectFunctionController::class, "index"])->middleware('project_permission:show');
    Route::post('functions', [ProjectFunctionController::class, "store"])->middleware('project_permission:create');
    Route::get('functions/{function}', [ProjectFunctionController::class, "show"])->middleware('project_permission:show');
    Route::put('functions/{function}', [ProjectFunctionController::class, "update"])->middleware('project_permission:edit');
    Route::delete('functions/{function}', [ProjectFunctionController::class, "destroy"])->middleware('project_permission:delete');


    Route::get('motiles', [MotileController::class, "index"])->middleware('project_permission:show');
    Route::post('motiles', [MotileController::class, "store"])->middleware('project_permission:create');
    Route::get('motiles/{motile}', [MotileController::class, "show"])->middleware('project_permission:show');
    Route::put('motiles/{mareReportMotileId}', [MotileController::class, "update"])->middleware('project_permission:edit');
    Route::delete('motiles/{mareReportMotileId}', [MotileController::class, "destroy"])->middleware('project_permission:delete');


    Route::prefix('selector')->group(function () {
        Route::get('indicators', [IndicatorController::class, "selector"]);
        Route::get('localities', [LocalityController::class, "selector"]);
        Route::get('taxa_categories', [TaxaCategoryController::class, "selector"]);
        Route::get('size_categories', [SizeCategoryController::class, "selector"]);
        Route::get('reports', [ReportController::class, "selector"]);
        Route::get('substrates', [SubstrateController::class, "selector"]);
        Route::get('depths', [DepthController::class, "selector"]);
        Route::get('functions', [ProjectFunctionController::class, "selector"]);
        Route::get('report-coordinates', FetchReportCoordinatesInvokable::class);
        Route::get('taxa-categories', [TaxaController::class, "selector"]);
    });
});
