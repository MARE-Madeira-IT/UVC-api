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
use App\Http\Controllers\SurveyProgramFunctionController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\MotileController;
use App\Http\Controllers\SurveyProgramController;
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
    Route::get('self-survey-programs', FetchSelfSurveyProgramsInvokable::class);


    Route::get("export/{surveyProgram}", [SurveyProgramController::class, "xlsxExport"]);
    Route::get('permissions/{surveyProgram}', [SurveyProgramController::class, "getSurveyProgramPermissions"]);

    Route::get('survey-program-statistics/{surveyProgram}', FetchSurveyProgramStatisticsInvokable::class)->middleware('survey_program_permission:show');

    Route::get('invites', FetchSurveyProgramInvitesInvokable::class);
    Route::post('invite-member', InviteMemberToSurveyProgramInvokable::class);
    Route::put('accept-member/{invite}', AcceptMemberToSurveyProgramInvokable::class);


    Route::get('localities', [LocalityController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('localities', [LocalityController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('localities/{locality}', [LocalityController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('localities/{locality}', [LocalityController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('localities/{locality}', [LocalityController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('taxas', [TaxaController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('taxas', [TaxaController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('taxas/{taxa}', [TaxaController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('taxas/{taxa}', [TaxaController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('taxas/{taxa}', [TaxaController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::post('taxas/photo/{taxa}', [TaxaController::class, "uploadPhoto"]);
    Route::put('taxas/toggle-validation/{taxa}', [TaxaController::class, "toggleValidation"]);

    Route::get('indicators', [IndicatorController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('indicators', [IndicatorController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('indicators/{indicator}', [IndicatorController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('indicators/{indicator}', [IndicatorController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('indicators/{indicator}', [IndicatorController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('reports', [ReportController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('reports', [ReportController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('reports/{report}', [ReportController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('reports/{report}', [ReportController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('reports/{report}', [ReportController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('surveyPrograms/members', [SurveyProgramController::class, "getMembers"]);
    Route::put('surveyPrograms/{survey_program_id}/members/{user_id}', [SurveyProgramController::class, "updateMember"]);
    Route::get('surveyPrograms', [SurveyProgramController::class, "index"]);
    Route::post('surveyPrograms', [SurveyProgramController::class, "store"]);
    Route::get('surveyPrograms/{surveyProgram}', [SurveyProgramController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('surveyPrograms/{surveyProgram}', [SurveyProgramController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('surveyPrograms/{surveyProgram}', [SurveyProgramController::class, "destroy"])->middleware('survey_program_permission:delete');

    Route::get('taxa_categories', [TaxaCategoryController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('taxa_categories', [TaxaCategoryController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('taxa_categories/{taxaCategory}', [TaxaCategoryController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('taxa_categories/{taxaCategory}', [TaxaCategoryController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('taxa_categories/{taxaCategory}', [TaxaCategoryController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('benthics', [BenthicController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('benthics', [BenthicController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('benthics/{id}', [BenthicController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('benthics/{benthic}', [BenthicController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('benthics/{benthic}', [BenthicController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('depths', [DepthController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('depths', [DepthController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('depths/{depth}', [DepthController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('depths/{depth}', [DepthController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('depths/{depth}', [DepthController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('substrates', [SubstrateController::class, "index"])->middleware('survey_program_permission:show');


    Route::get('functions', [SurveyProgramFunctionController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('functions', [SurveyProgramFunctionController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('functions/{function}', [SurveyProgramFunctionController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('functions/{function}', [SurveyProgramFunctionController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('functions/{function}', [SurveyProgramFunctionController::class, "destroy"])->middleware('survey_program_permission:delete');


    Route::get('motiles', [MotileController::class, "index"])->middleware('survey_program_permission:show');
    Route::post('motiles', [MotileController::class, "store"])->middleware('survey_program_permission:create');
    Route::get('motiles/{motile}', [MotileController::class, "show"])->middleware('survey_program_permission:show');
    Route::put('motiles/{mareReportMotileId}', [MotileController::class, "update"])->middleware('survey_program_permission:edit');
    Route::delete('motiles/{mareReportMotileId}', [MotileController::class, "destroy"])->middleware('survey_program_permission:delete');


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
        Route::get('taxa-categories', [TaxaController::class, "selector"]);
    });
});
