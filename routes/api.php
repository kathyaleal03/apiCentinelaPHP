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
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EstadisticasController;

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

Route::apiResource('/regiones', RegionController::class);
Route::apiResource('/fotos', FotoReporteController::class);
Route::apiResource('/reportes', ReporteController::class);

Route::get('/alertas/getAllAlert', [AlertaController::class, 'getAllAlert']);

Route::post('/alertas/createAlert', [AlertaController::class, 'createAlerta']);
Route::apiResource('/alertas', AlertaController::class);


Route::apiResource('/comentarios', ComentarioController::class);

// Estadísticas de Reportes
Route::prefix('/reportes/estadisticas')->group(function () {
    Route::get('/tipos', [EstadisticasController::class, 'tipos']);
    Route::get('/estados', [EstadisticasController::class, 'estados']);
    Route::get('/regiones', [EstadisticasController::class, 'regiones']);
    Route::get('/heatmap', [EstadisticasController::class, 'heatmap']);
});

// Estadísticas de Alertas
Route::prefix('/estadisticas/alertas')->group(function () {
    Route::get('/niveles', [EstadisticasController::class, 'nivelesAlerta']);
    Route::get('/regiones', [EstadisticasController::class, 'alertasPorRegion']);
});

// Estadísticas de Emergencias
Route::prefix('/estadisticas/emergencias')->group(function () {
    Route::get('/atendidos', [EstadisticasController::class, 'emergenciasAtendidas']);
});

// Estadísticas generales 
Route::prefix('/estadisticas')->group(function () {
    Route::get('/dashboard', [EstadisticasController::class, 'dashboard']);
});

Route::apiResource('/emergencias', EmergenciaController::class);

// User auth routes
Route::prefix('/usuarios')->group(function () {
    Route::post('/createUser', [UsuarioController::class, 'register']);
    Route::post('/login', [UsuarioController::class, 'login']);
    Route::post('/logout', [UsuarioController::class, 'logout']);
    Route::put('/rol/{id}', [UsuarioController::class, 'updateRol']);
    Route::post('/{id}/cambiar-contrasena', [UsuarioController::class, 'changePassword']);
});


Route::apiResource('/usuarios', UsuarioController::class)->except(['store', 'create', 'edit']);