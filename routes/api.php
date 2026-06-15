<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\AmiController;

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

// ─── Routes Amis (authentification requise) ──────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    // Recherche d'utilisateurs : GET /api/utilisateurs?search=marie
    Route::get('/utilisateurs', [AmiController::class, 'search']);

    // CRUD amis
    Route::get('/amis', [AmiController::class, 'index']);       // Mes amis
    Route::post('/amis', [AmiController::class, 'store']);      // Envoyer une demande
    Route::patch('/amis/{id}', [AmiController::class, 'update']); // Accepter / Refuser
    Route::delete('/amis/{id}', [AmiController::class, 'destroy']); // Supprimer / Annuler
});

