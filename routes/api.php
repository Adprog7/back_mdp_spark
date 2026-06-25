<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\BilletController; 
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrganizerController; 

// Assure-toi que cette ligne existe
Route::get('/evenements/recents', [EvenementController::class, 'getRecents']);
/*
|--------------------------------------------------------------------------
| Routes Publiques (Sans authentification)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/organizer/login', [AuthController::class, 'organizerLogin']);
Route::post('/organizer/register', [AuthController::class, 'organizerRegister']);

Route::get('/evenements', [EvenementController::class, 'index']);
Route::get('/evenements/{id}', [EvenementController::class, 'show']);

Route::get('/test-connexion', function () {
    return response()->json([
        'statut' => 'succès',
        'message' => 'Coucou depuis le back Laravel !'
    ]);
});

/*
|--------------------------------------------------------------------------
| Routes Protégées (Nécessitent le token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/user/stats', function (Request $request) {
        $user = $request->user();
        return response()->json([
            // Correction ici : table 'billets' au lieu de 'billet'
            'evenements' => DB::table('billets')->where('id_utilisateur', $user->id_utilisateur)->count(),
            'groupes' => DB::table('membres_groupe')->where('id_utilisateur', $user->id_utilisateur)->count(),
            'favoris' => DB::table('favoris')->where('id_utilisateur', $user->id_utilisateur)->count(),
        ]);
    });

    // Groupes
    Route::get('/groupes', [GroupeController::class, 'index']);
    Route::get('/groupes/{id}/membres', [GroupeController::class, 'getMembres']);

    // Billets
    Route::get('/my-tickets', [BilletController::class, 'index']);
    Route::post('/billets', [BilletController::class, 'store']);

    // api.php
    Route::get('/messages/{id_groupe}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);

    Route::get('/organizer/profile', [OrganizerController::class, 'getProfile']);
    Route::get('/organizer/events', [OrganizerController::class, 'getEvents']);
    Route::get('/organizer/stats', [OrganizerController::class, 'getStats']);

    Route::post('/evenements/store', [EvenementController::class, 'store']);
});