<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\FotoReporteController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\AlertaController;
use App\Http\Controllers\Api\ComentarioController;
use App\Http\Controllers\Api\EmergenciaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API resource routes for the new models
Route::apiResource('regiones', RegionController::class);
Route::apiResource('fotos', FotoReporteController::class);
Route::apiResource('reportes', ReporteController::class);
Route::apiResource('alertas', AlertaController::class);
Route::apiResource('comentarios', ComentarioController::class);
Route::apiResource('emergencias', EmergenciaController::class);
