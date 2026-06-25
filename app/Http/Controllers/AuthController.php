<?php

namespace App\Http\Controllers;

use App\Models\Organisateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Correction : Hash::check compare avec le mot_de_passe
        if (!$user || !Hash::check($request->password, $user->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Connexion réussie', 'token' => $token, 'user' => $user]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:utilisateur,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Compte créé', 'token' => $token, 'user' => $user], 201);
    }

    public function organizerRegister(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:150|unique:utilisateur,email', // Table 'utilisateur'
            'password' => 'required|string|min:8',
            'siret' => 'required|string',
        ]);

        $result = DB::transaction(function () use ($request) {
            // 1. Création de l'utilisateur avec tes noms de colonnes réels
            $user = User::create([
                'prenom' => $request->prenom,
                'nom' => $request->nom, 
                'email' => $request->email,
                'mot_de_passe' => Hash::make($request->password), // Utilise le nom de ta colonne
                'date_inscription' => now(), // Important : Laravel ne le fait pas seul si tes timestamps sont à false
                'langue' => 'fr',
            ]);

            // 2. Création de l'organisateur
            $organizer = Organisateur::create([
                'nom_structure' => $request->nom . ' ' . $request->prenom, 
                'type_structure' => 'Organisation',
                'description' => 'Inscription mobile', // Ajout d'une valeur par défaut pour éviter le NULL
                'id_utilisateur' => $user->id_utilisateur, // Utilise la clé primaire de ton modèle
            ]);

            return [$user, $organizer];
        });

        [$user, $organizer] = $result;
        $token = $user->createToken('organizer_auth_token')->plainTextToken;
        
        return response()->json([
            'message' => 'Organisateur créé', 
            'token' => $token, 
            'user' => $user
        ], 201);
    }
    public function organizerLogin(Request $request)
    {
        // 1. Validation des champs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Recherche de l'utilisateur
        $user = User::where('email', $request->email)->first();

        // 3. Vérification du mot de passe
        // Note: Utilise 'mot_de_passe' car c'est le nom de ta colonne en BDD
        if (!$user || !Hash::check($request->password, $user->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // 4. Vérification optionnelle : est-ce bien un organisateur ?
        // Tu peux vérifier si une entrée existe dans la table 'organisateurs' pour cet utilisateur
        $isOrganizer = Organisateur::where('id_utilisateur', $user->id_utilisateur)->exists();
        if (!$isOrganizer) {
            return response()->json(['message' => 'Ce compte n\'est pas un compte organisateur'], 403);
        }

        // 5. Génération du token
        $token = $user->createToken('organizer_auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion organisateur réussie',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}