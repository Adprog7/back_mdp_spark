<?php

namespace App\Services;

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    /**
     * US 1 : Créer un compte utilisateur
     * En tant que visiteur, je veux créer un compte afin d'accéder aux fonctionnalités de réservation.
     */
    public function register(array $data): Utilisateur
    {
        return Utilisateur::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mot_de_passe' => Hash::make($data['password']),
            'langue' => $data['langue'] ?? 'fr',
        ]);
    }

    /**
     * US 2 : Se connecter de manière sécurisée
     * En tant qu'utilisateur, je veux me connecter de manière sécurisée afin de retrouver mes billets et mes cercles.
     */
    public function login(string $email, string $password): bool|Utilisateur
    {
        $utilisateur = Utilisateur::where('email', $email)->first();

        if (!$utilisateur || !Hash::check($password, $utilisateur->mot_de_passe)) {
            return false;
        }

        Auth::login($utilisateur);
        return $utilisateur;
    }

    /**
     * US 3 : Se déconnecter
     * En tant qu'utilisateur, je veux me déconnecter afin de protéger l'accès à mes données personnelles.
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Vérifier si un email existe
     */
    public function emailExists(string $email): bool
    {
        return Utilisateur::where('email', $email)->exists();
    }

    /**
     * Récupérer l'utilisateur actuel
     */
    public function getCurrentUser(): ?Utilisateur
    {
        return Auth::guard()->user();
    }

    /**
     * Mettre à jour le profil de l'utilisateur
     */
    public function updateProfile(Utilisateur $utilisateur, array $data): Utilisateur
    {
        $utilisateur->update($data);
        return $utilisateur;
    }
}
