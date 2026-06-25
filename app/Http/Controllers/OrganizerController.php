<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evenement;
use App\Models\Organisateur;
use Illuminate\Support\Facades\Log;

class OrganizerController extends Controller
{
    public function getProfile(Request $request)
{
    try {
        // Ajoute ceci pour être sûr que l'utilisateur est bien identifié
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $organisateur = \App\Models\Organisateur::where('id_utilisateur', $user->id_utilisateur)->first();
        
        if (!$organisateur) {
            return response()->json(['error' => 'Profil organisateur introuvable pour cet utilisateur'], 404);
        }

        return response()->json([
            'nom_complet' => $user->prenom . ' ' . $user->nom,
            'username' => 'organisateur_' . $organisateur->id_organisateur,
            'avatar' => null
        ]);

    } catch (\Exception $e) {
        // C'est cette ligne qui va nous donner la réponse dans le navigateur
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}

    public function getEvents(Request $request)
    {
        return Evenement::where('organisateur_id', $request->user()->id_utilisateur)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->titre,        // CORRIGÉ : utilise 'titre'
                    'date' => $event->date_debut,    // CORRIGÉ : utilise 'date_debut'
                    'lieu' => $event->lieu,
                    'status' => 'En ligne',
                    'statusColor' => 'bg-green-100 text-green-600',
                    'vendus' => 0,                   // À calculer dynamiquement plus tard
                    'taux' => '0 %',
                    'revenus' => '0 €',
                    'image' => $event->photo         // CORRIGÉ : utilise 'photo'
                ];
            });
    }

    public function getStats(Request $request)
{
    $userId = $request->user()->id_utilisateur;

    // 1. Compter les évènements de l'organisateur
    $nbEvenements = Evenement::where('organisateur_id', $userId)->count();

    // 2. Récupérer les IDs des événements de cet organisateur
    $evenementIds = Evenement::where('organisateur_id', $userId)->pluck('id');

    // 3. Calculer les totaux via la table 'billets'
    // On utilise DB::table car tu n'as peut-être pas encore le modèle Billet.php
    $statsBillets = \Illuminate\Support\Facades\DB::table('billets')
        ->whereIn('id_evenement', $evenementIds) // Correction ici : id_evenement
        ->selectRaw('count(*) as total_billets, sum(prix) as total_revenus')
        ->first();

    $nbBillets = $statsBillets->total_billets ?? 0;
    $totalRevenus = $statsBillets->total_revenus ?? 0;

    return [
        ['label' => "Évènements", 'value' => (string)$nbEvenements, 'icon' => "✦"],
        ['label' => "Billets vendus", 'value' => (string)$nbBillets, 'icon' => "◇"],
        ['label' => "Revenus", 'value' => number_format($totalRevenus, 0, ',', ' ') . ' €', 'icon' => "€"],
        ['label' => "Note moyenne", 'value' => "4.8/5", 'icon' => "☆"],
    ];
}
    
}