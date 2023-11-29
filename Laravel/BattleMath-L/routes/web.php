<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespostaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/preguntes', [PreguntaController::class, 'adminIndex'])->name('preguntes');
Route::get('/preguntes/afegir', function () { return view('preguntes.afegir');})->name('view-afegir-pregunta');
Route::post('/preguntes/afegir', [PreguntaController::class, 'adminStore'])->name('afegir-pregunta');
Route::get('/preguntes/modificar/{id}', [PreguntaController::class, 'adminShow'])->name('view-modificar-pregunta');
Route::patch('/preguntes/modificar/{id}', [PreguntaController::class, 'adminUpdate'])->name('modificar-pregunta');



Route::get('/respostes', [RespostaController::class, 'adminIndex'])->name('respostes');
Route::get('/respostes/afegir', function () { return view('respostes.afegir');})->name('view-afegir-resposta');
Route::post('/respostes/afegir', [RespostaController::class, 'adminStore'])->name('afegir-resposta');
