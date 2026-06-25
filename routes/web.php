<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/devenir-organisateur', function () {
    return view('devenir-organisateur');
})->name('devenir-organisateur');

Route::get('/login', function () {
    return response()->json(['message' => 'Veuillez vous connecter.'], 401);
})->name('login');