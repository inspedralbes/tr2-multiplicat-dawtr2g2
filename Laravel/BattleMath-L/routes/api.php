<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespostaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/preguntes', [PreguntaController::class, 'index']);
Route::get('/respostes', [RespostaController::class, 'index']);
Route::post('/crearRespostes', [RespostaController::class, 'store']);
Route::get('/veurePregunta/{id}', [PreguntaController::class, 'show']);
Route::get('/veureResposta/{id}', [RespostaController::class, 'show']);
Route::put('/modificarPregunta/{id}', [PreguntaController::class, 'update']);
Route::put('/modificarResposta/{id}', [RespostaController::class, 'update']);
Route::delete('/eliminarResposta/{id}', [RespostaController::class, 'destroy']);
