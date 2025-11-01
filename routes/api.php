<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Ruta para realizar la carga inicial de datos.
Route::post('/perform-init-load', [ApiController::class, 'performInitLoad']);

// Ruta para realizar el barrido de datos.
Route::post('/perform-sweep', [ApiController::class, 'performSweep']);

// Ruta para generar el reporte final con métricas.
Route::get('/generate-report', [ApiController::class, 'generateReport']);

// Ruta para gestionar los resultados.(CRUD)
Route::apiResource('results', ResultController::class);
