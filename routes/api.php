<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\AmiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json(['message' => 'Connexion réussie entre le front et le back !']);
});

// Routes Événements
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

