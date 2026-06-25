<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // AJOUTE CETTE LIGNE
use Illuminate\Support\Facades\DB; // AJOUTE CETTE LIGNE
use App\Models\Groupe;

class GroupeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Debug : vérifie ce que renvoie la base avec une requête simple
        $groupes = \DB::table('groupes')
            ->join('membres_groupe', 'groupes.id', '=', 'membres_groupe.id_groupe')
            ->where('membres_groupe.id_utilisateur', $user->id_utilisateur)
            ->select('groupes.id as id_groupe', 'groupes.nom')
            ->distinct()
            ->get();

        return response()->json($groupes);
    }

    public function getMembres($id)
    {
        // On récupère les utilisateurs membres du groupe via une jointure SQL
        // Dans GroupeController@getMembres
        $membres = \DB::table('utilisateur')
            ->join('membres_groupe', 'utilisateur.id_utilisateur', '=', 'membres_groupe.id_utilisateur')
            ->where('membres_groupe.id_groupe', $id)
            ->select('utilisateur.id_utilisateur as id', 'utilisateur.prenom as name')
            ->distinct() // Empêche les doublons
            ->get();

        // On vérifie si la liste est vide pour éviter des erreurs
        if ($membres->isEmpty()) {
            // Retourne un tableau vide au lieu d'une 404 si le groupe existe mais est vide
            // ou vérifie ici si le groupe existe vraiment avec DB::table('groupes')->find($id)
        }

        return response()->json($membres);
    }
}