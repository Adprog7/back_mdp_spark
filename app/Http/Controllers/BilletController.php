<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BilletController extends Controller
{
    // Récupération de la liste des billets de l'utilisateur connecté
    public function index(Request $request)
    {
        $userId = $request->user()->id_utilisateur;
        
        return DB::table('billets')
            ->join('evenements', 'billets.id_evenement', '=', 'evenements.id')
            ->select(
                'billets.id_billet', 
                'billets.prix', 
                'billets.date_achat', 
                'billets.statut', 
                'evenements.titre', 
                'evenements.photo', 
                'evenements.date_debut'
            )
            ->where('billets.id_utilisateur', $userId)
            ->get();
    }

    // Enregistrement d'un nouveau billet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_evenement' => 'required|integer',
            'id_destinataire' => 'nullable|integer'
        ]);

        $userId = $request->id_destinataire ?? $request->user()->id_utilisateur;

        // Récupérer le prix depuis la table evenements
        $evenement = \DB::table('evenements')->where('id', $validated['id_evenement'])->first();
        $prix = $evenement ? $evenement->prix : 0;

        \DB::table('billets')->insert([
            'id_utilisateur' => $userId, 
            'id_evenement' => $validated['id_evenement'],
            'prix' => $prix,
            'date_achat' => now()->format('Y-m-d H:i:s'),
            'statut' => 'valide',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
}