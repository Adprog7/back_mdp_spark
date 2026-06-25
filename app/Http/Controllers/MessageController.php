<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($id_groupe)
    {
        // Récupération avec les noms de colonnes exacts de ta BDD
        return DB::table('messages')
            ->where('id_groupe', $id_groupe)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'text' => $message->text,
                    // Comparaison stricte avec l'ID de l'utilisateur connecté
                    'is_mine' => $message->id_utilisateur == Auth::id(),
                    // Avatar dynamique basé sur l'ID de l'expéditeur
                    'sender_avatar' => "https://ui-avatars.com/api/?name=U" . $message->id_utilisateur
                ];
            });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_groupe' => 'required|integer',
            'text' => 'required|string'
        ]);

        DB::table('messages')->insert([
            'id_groupe' => $validated['id_groupe'],
            'id_utilisateur' => Auth::id(), // Utilise l'ID de l'utilisateur authentifié
            'text' => $validated['text'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
    // app/Http/Controllers/GroupeController.php
    public function join(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        // Trouver le groupe par son lien_invitation
        $groupe = DB::table('groupes')->where('lien_invitation', $request->code)->first();

        if (!$groupe) {
            return response()->json(['message' => 'Groupe introuvable'], 404);
        }

        // Ajouter l'utilisateur à la table membres_groupe
        DB::table('membres_groupe')->updateOrInsert(
            ['id_utilisateur' => Auth::id(), 'id_groupe' => $groupe->id_groupe],
            ['created_at' => now()]
        );

        return response()->json(['id_groupe' => $groupe->id_groupe]);
    }
}