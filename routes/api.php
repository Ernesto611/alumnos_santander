<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AlumnoController;

Route::post('/alumnos', [AlumnoController::class, 'store']);
Route::get('/alumnos', [AlumnoController::class, 'index']);
Route::get('/alumnos/{matricula}', [AlumnoController::class, 'show']);