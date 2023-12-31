<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespostaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkinsController;
use App\Http\Controllers\DificultatController;
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
//PUBLIC ROUTES

//Preguntes
Route::get('/preguntes', [PreguntaController::class, 'index']);
Route::post('/preguntes/crear', [PreguntaController::class, 'store']);
Route::get('/preguntes/mostrar/{id}', [PreguntaController::class, 'show']);
Route::get('/preguntes/dificultat/{dif}', [PreguntaController::class, 'showPregDif']);
Route::put('/preguntes/modificar/{id}', [PreguntaController::class, 'update']);
Route::delete('/preguntes/eliminar/{id}', [PreguntaController::class, 'destroy']);

//Respostes 
Route::delete('/respostes/eliminar/{id}', [RespostaController::class, 'destroy']);
Route::put('/respostes/modificar/{id}', [RespostaController::class, 'update']);
Route::get('/respostes/mostrar/{id}', [RespostaController::class, 'show']);
Route::post('/respostes/crear', [RespostaController::class, 'store']);
Route::get('/respostes', [RespostaController::class, 'index']);

//Auth
Route::post('/registre', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::put('/perfil/modificar/{id}', [AuthController::class,'update']);

//PROTECTED ROUTES
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class,'logout']);
});

///Personatges
Route::get('/skins', [SkinsController::class, 'index']);

//DIFICULTATS
Route::get('/getDamage/{id}', [DificultatController::class, 'getDamage']);