<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\FotoReporteController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\AlertaController;
use App\Http\Controllers\Api\ComentarioController;
use App\Http\Controllers\Api\EmergenciaController;
use App\Http\Controllers\Api\UsuarioController;

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


Route::apiResource('/regiones', RegionController::class);
Route::apiResource('/fotos', FotoReporteController::class);
Route::apiResource('/reportes', ReporteController::class);

Route::get('/alertas/getAllAlert', [AlertaController::class, 'getAllAlert']);

Route::post('/alertas/createAlert', [AlertaController::class, 'createAlerta']);
Route::apiResource('/alertas', AlertaController::class);


Route::apiResource('/comentarios', ComentarioController::class);
Route::apiResource('/emergencias', EmergenciaController::class);

// User auth routes
Route::prefix('/usuarios')->group(function () {
    Route::post('/register', [UsuarioController::class, 'register']);
    Route::post('/login', [UsuarioController::class, 'login']);
    Route::post('/logout', [UsuarioController::class, 'logout'])->middleware('auth:sanctum');
    Route::put('/rol/{id}', [UsuarioController::class, 'updateRol'])->middleware('auth:sanctum');
});

// Resource routes for usuarios (index, show, update, destroy)
// `store` is handled by POST /api/usuarios/register to keep the Java-style payload naming
Route::apiResource('/usuarios', UsuarioController::class)->except(['store', 'create', 'edit']);
