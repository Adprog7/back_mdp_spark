<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvenementController;

Route::get('/user', function (Request $request) {
    $user = $request->user();
    $user->load('organisateur');
    return response()->json($user);
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json(['message' => 'Connexion réussie entre le front et le back !']);
});

use App\Http\Controllers\OrganizerDashboardController;
use App\Http\Controllers\AuthController;

// Authentification
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Routes protégées par l'authentification
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/evenements', [EvenementController::class, 'store']);
});

// Routes Événements (Publiques)
Route::get('/evenements', [EvenementController::class, 'index']);
Route::get('/evenements/{id}', [EvenementController::class, 'show']);

// Routes Dashboard Organisateur
Route::get('/organizer/{id}/dashboard-stats', [OrganizerDashboardController::class, 'getStats']);
Route::get('/organizer/{id}/events', [OrganizerDashboardController::class, 'getEvents']);
