<?php

use App\Http\Controllers\SurveyProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
