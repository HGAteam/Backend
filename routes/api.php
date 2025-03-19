<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\PeopleController;
use App\Http\Controllers\API\PlanetController;
use App\Http\Controllers\API\VehicleController;
use Illuminate\Support\Facades\Route;

// Endpoints públicos (Sin autenticación)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/is-authenticated', [AuthController::class, 'isAuth'])->middleware('auth:api');

// Acceso para invitados (todos los usuarios autenticados)
Route::middleware(['auth:api','exception.handler'])->group(function () {
    Route::get('/people', [PeopleController::class, 'index'])->name('people.index'); // GET All con limit y offset
    Route::get('/people/{id}', [PeopleController::class, 'show'])->name('people.show'); // GET by ID

    Route::get('/planets', [PlanetController::class, 'index'])->name('planets.index'); // GET All con limit y offset
    Route::get('/planets/{id}', [PlanetController::class, 'show'])->name('planets.show'); // GET by ID

    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index'); // GET All con limit y offset
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show'); // GET by ID
});
