<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartementController;
use App\Http\Controllers\Api\SalleController;
use App\Http\Controllers\Api\CameraController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\RapportController;

// Routes publiques
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Admin uniquement
    Route::middleware('role:administrateur')->group(function () {
        Route::apiResource('users',        UserController::class);
        Route::apiResource('departements', DepartementController::class);
    });

    // Tous les rôles
    Route::apiResource('salles',  SalleController::class);
    Route::apiResource('cameras', CameraController::class);

    // Incidents
    Route::get   ('incidents',                   [IncidentController::class, 'index']);
    Route::post  ('incidents',                   [IncidentController::class, 'store']);
    Route::get   ('incidents/{incident}',        [IncidentController::class, 'show']);
    Route::patch ('incidents/{incident}/statut', [IncidentController::class, 'changerStatut']);
    Route::delete('incidents/{incident}',        [IncidentController::class, 'destroy']);

    // Evaluations
    Route::apiResource('evaluations', EvaluationController::class)->except(['update']);

    // Rapports
    Route::get ('rapports',                    [RapportController::class, 'index']);
    Route::post('rapports',                    [RapportController::class, 'store']);
    Route::get ('rapports/{rapport}/download', [RapportController::class, 'download']);
});