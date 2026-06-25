<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvenementController extends Controller
{
    public function index()
    {
        return response()->json(Evenement::orderBy('date_debut')->get());
    }

    public function show($id)
    {
        // On force le chargement de la relation 'organisateur'
        $evenement = Evenement::with('organisateur')->find($id);

        if (!$evenement) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }

        return response()->json($evenement);
    }

    // AJOUTE CETTE MÉTHODE POUR LA PUBLICATION
    public function store(Request $request)
    {
        try {
            // 1. Validation selon ta table SQL
            $request->validate([
                'titre' => 'required|string|max:191',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date',
                'lieu' => 'required|string|max:191',
                'prix' => 'required|numeric',
                'capacite' => 'required|integer',
                'photo' => 'nullable|image|max:2048', // 2MB max
            ]);

            // 2. Gestion de l'image (si présente)
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('events', 'public');
            }

            // 3. Création de l'événement
            $evenement = Evenement::create([
                'titre' => $request->titre,
                'description' => $request->description ?? 'Pas de description',
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'lieu' => $request->lieu,
                'prix' => $request->prix,
                'capacite' => $request->capacite,
                'photo' => $photoPath,
                'organisateur_id' => $request->user()->id_utilisateur, // Utilise l'ID de l'utilisateur connecté
                'statut' => 'actif',
            ]);

            return response()->json(['message' => 'Événement créé avec succès', 'data' => $evenement], 201);

        } catch (\Exception $e) {
            Log::error("Erreur création événement : " . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création', 'error' => $e->getMessage()], 500);
        }
    }
}