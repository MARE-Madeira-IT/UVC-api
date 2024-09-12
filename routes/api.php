<?php

use App\Http\Controllers\AcceptMemberToMareProjectInvokable;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FetchMareProjectInvitesInvokable;
use App\Http\Controllers\FetchMareProjectStatisticsInvokable;
use App\Http\Controllers\FetchMareReportCoordinatesInvokable;
use App\Http\Controllers\FetchSelfProjectsInvokable;
use App\Http\Controllers\InviteMemberToMareProjectInvokable;
use App\Http\Controllers\MareBenthicController;
use App\Http\Controllers\MareDepthController;
use App\Http\Controllers\MareFunctionController;
use App\Http\Controllers\MareIndicatorController;
use App\Http\Controllers\MareLocalityController;
use App\Http\Controllers\MareMotileController;
use App\Http\Controllers\MareProjectController;
use App\Http\Controllers\MareReportController;
use App\Http\Controllers\MareSizeCategoryController;
use App\Http\Controllers\MareSubstrateController;
use App\Http\Controllers\MareTaxaCategoryController;
use App\Http\Controllers\MareTaxaController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [AuthController::class, "logout"]);
    Route::get('self-projects', FetchSelfProjectsInvokable::class);
});

Route::get("export/{project}", [MareProjectController::class, "xlsxExport"]);
Route::get('permissions/{project}', [MareProjectController::class, "getProjectPermissions"]);

Route::get('project-statistics/{project}', FetchMareProjectStatisticsInvokable::class)->middleware('mare_project_permission:show');

Route::get('invites', FetchMareProjectInvitesInvokable::class);
Route::post('invite-member', InviteMemberToMareProjectInvokable::class);
Route::put('accept-member/{invite}', AcceptMemberToMareProjectInvokable::class);


Route::get('localities', [MareLocalityController::class, "index"])->middleware('mare_project_permission:show');
Route::post('localities', [MareLocalityController::class, "store"])->middleware('mare_project_permission:create');
Route::get('localities/{locality}', [MareLocalityController::class, "show"])->middleware('mare_project_permission:show');
Route::put('localities/{locality}', [MareLocalityController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('localities/{locality}', [MareLocalityController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::get('taxas', [MareTaxaController::class, "index"])->middleware('mare_project_permission:show');
Route::post('taxas', [MareTaxaController::class, "store"])->middleware('mare_project_permission:create');
Route::get('taxas/{taxa}', [MareTaxaController::class, "show"])->middleware('mare_project_permission:show');
Route::put('taxas/{taxa}', [MareTaxaController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('taxas/{taxa}', [MareTaxaController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::post('taxas/photo/{taxa}', [MareTaxaController::class, "uploadPhoto"]);
Route::put('taxas/toggle-validation/{taxa}', [MareTaxaController::class, "toggleValidation"]);

Route::get('indicators', [MareIndicatorController::class, "index"])->middleware('mare_project_permission:show');
Route::post('indicators', [MareIndicatorController::class, "store"])->middleware('mare_project_permission:create');
Route::get('indicators/{indicator}', [MareIndicatorController::class, "show"])->middleware('mare_project_permission:show');
Route::put('indicators/{indicator}', [MareIndicatorController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('indicators/{indicator}', [MareIndicatorController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::get('reports', [MareReportController::class, "index"])->middleware('mare_project_permission:show');
Route::post('reports', [MareReportController::class, "store"])->middleware('mare_project_permission:create');
Route::get('reports/{report}', [MareReportController::class, "show"])->middleware('mare_project_permission:show');
Route::put('reports/{report}', [MareReportController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('reports/{report}', [MareReportController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::get('projects/members', [MareProjectController::class, "getMembers"]);
Route::put('projects/{project_id}/members/{user_id}', [MareProjectController::class, "updateMember"]);
Route::get('projects', [MareProjectController::class, "index"]);
Route::post('projects', [MareProjectController::class, "store"]);
Route::get('projects/{project}', [MareProjectController::class, "show"])->middleware('mare_project_permission:show');
Route::put('projects/{project}', [MareProjectController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('projects/{project}', [MareProjectController::class, "destroy"])->middleware('mare_project_permission:delete');

Route::get('taxa_categories', [MareTaxaCategoryController::class, "index"])->middleware('mare_project_permission:show');

Route::get('benthics', [MareBenthicController::class, "index"])->middleware('mare_project_permission:show');
Route::post('benthics', [MareBenthicController::class, "store"])->middleware('mare_project_permission:create');
Route::get('benthics/{id}', [MareBenthicController::class, "show"])->middleware('mare_project_permission:show');
Route::put('benthics/{benthic}', [MareBenthicController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('benthics/{benthic}', [MareBenthicController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::get('depths', [MareDepthController::class, "index"])->middleware('mare_project_permission:show');
Route::post('depths', [MareDepthController::class, "store"])->middleware('mare_project_permission:create');
Route::get('depths/{depth}', [MareDepthController::class, "show"])->middleware('mare_project_permission:show');
Route::put('depths/{depth}', [MareDepthController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('depths/{depth}', [MareDepthController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::get('substrates', [MareSubstrateController::class, "index"])->middleware('mare_project_permission:show');


Route::get('functions', [MareFunctionController::class, "index"])->middleware('mare_project_permission:show');
Route::post('functions', [MareFunctionController::class, "store"])->middleware('mare_project_permission:create');
Route::get('functions/{function}', [MareFunctionController::class, "show"])->middleware('mare_project_permission:show');
Route::put('functions/{function}', [MareFunctionController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('functions/{function}', [MareFunctionController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::get('motiles', [MareMotileController::class, "index"])->middleware('mare_project_permission:show');
Route::post('motiles', [MareMotileController::class, "store"])->middleware('mare_project_permission:create');
Route::get('motiles/{motile}', [MareMotileController::class, "show"])->middleware('mare_project_permission:show');
Route::put('motiles/{mareReportMotileId}', [MareMotileController::class, "update"])->middleware('mare_project_permission:edit');
Route::delete('motiles/{mareReportMotileId}', [MareMotileController::class, "destroy"])->middleware('mare_project_permission:delete');


Route::prefix('selector')->group(function () {
    Route::get('indicators', [MareIndicatorController::class, "selector"]);
    Route::get('localities', [MareLocalityController::class, "selector"]);
    Route::get('taxa_categories', [MareTaxaCategoryController::class, "selector"]);
    Route::get('size_categories', [MareSizeCategoryController::class, "selector"]);
    Route::get('reports', [MareReportController::class, "selector"]);
    Route::get('substrates', [MareSubstrateController::class, "selector"]);
    Route::get('depths', [MareDepthController::class, "selector"]);
    Route::get('functions', [MareFunctionController::class, "selector"]);
    Route::get('report-coordinates', FetchMareReportCoordinatesInvokable::class);
    Route::get('taxa-categories', [MareTaxaController::class, "selector"]);
});
