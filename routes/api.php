<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CameraController;
use App\Http\Controllers\Api\DepartementController;
use App\Http\Controllers\Api\EcController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\FormationController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\RapportController;
use App\Http\Controllers\Api\SalleController;
use App\Http\Controllers\Api\SurveillantController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('role:administrateur')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('departements', DepartementController::class);
    });

    Route::middleware('role:administrateur,chef_scolarite')->group(function () {
        Route::apiResource('formations', FormationController::class)->except(['index', 'show']);
        Route::apiResource('ecs', EcController::class)->except(['index', 'show']);
        Route::apiResource('surveillants', SurveillantController::class)->except(['index', 'show']);
        Route::apiResource('salles', SalleController::class)->except(['index', 'show']);
        Route::apiResource('cameras', CameraController::class)->except(['index', 'show']);
        Route::apiResource('evaluations', EvaluationController::class)->except(['index', 'show', 'update']);
        Route::apiResource('videos', VideoController::class)->except(['index', 'show']);
    });

    Route::apiResource('formations', FormationController::class)->only(['index', 'show']);
    Route::apiResource('ecs', EcController::class)->only(['index', 'show']);
    Route::apiResource('surveillants', SurveillantController::class)->only(['index', 'show']);
    Route::apiResource('salles', SalleController::class)->only(['index', 'show']);
    Route::apiResource('cameras', CameraController::class)->only(['index', 'show']);
    Route::apiResource('evaluations', EvaluationController::class)->only(['index', 'show']);
    Route::apiResource('videos', VideoController::class)->only(['index', 'show']);

    Route::get('incidents', [IncidentController::class, 'index']);
    Route::post('incidents', [IncidentController::class, 'store']);
    Route::get('incidents/{incident}', [IncidentController::class, 'show']);
    Route::patch('incidents/{incident}/statut', [IncidentController::class, 'changerStatut']);
    Route::delete('incidents/{incident}', [IncidentController::class, 'destroy'])
        ->middleware('role:administrateur,chef_scolarite');

    Route::get('rapports', [RapportController::class, 'index']);
    Route::post('rapports', [RapportController::class, 'store']);
    Route::get('rapports/{rapport}/download', [RapportController::class, 'download']);
});
