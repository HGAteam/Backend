<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->intended('/docs');
});

// Rutas de Laravel Passport
Route::prefix('oauth')->group(function () {
    Route::post('/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');
    Route::post('/token/refresh', '\Laravel\Passport\Http\Controllers\TransientTokenController@refresh')->name('passport.token.refresh');
    Route::delete('/token/revoke', '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy')->name('passport.token.revoke');
});