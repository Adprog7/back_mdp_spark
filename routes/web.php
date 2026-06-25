<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/devenir-organisateur', function () {
    return view('devenir-organisateur');
})->name('devenir-organisateur');
