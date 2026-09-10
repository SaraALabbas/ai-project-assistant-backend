<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectAnalysisController;

Route::post('/analyze-project', [ProjectAnalysisController::class, 'analyze']);