<?php

namespace App\Services;

use App\Models\Organisateur;
use App\Models\Utilisateur;

class OrganizerRequestService
{
    /**
     * US 4 : Demander un statut "Organisateur"
     * En tant qu'utilisateur, je veux demander un statut "Organisateur" afin de pouvoir créer et gérer mes propres événements.
     */
    public function requestOrganizerStatus(Utilisateur $utilisateur, array $data): Organisateur
    {
        return Organisateur::create([
            'nom_structure' => $data['nom_structure'],
            'type_structure' => $data['type_structure'],
            'description' => $data['description'] ?? null,
            'id_utilisateur' => $utilisateur->id_utilisateur,
        ]);
    }

    /**
     * Vérifier si un utilisateur est organisateur
     */
    public function isOrganizer(Utilisateur $utilisateur): bool
    {
        return Organisateur::where('id_utilisateur', $utilisateur->id_utilisateur)->exists();
    }

    /**
     * Récupérer les informations d'organisateur
     */
    public function getOrganizerInfo(Utilisateur $utilisateur): ?Organisateur
    {
        return Organisateur::where('id_utilisateur', $utilisateur->id_utilisateur)->first();
    }

    /**
     * Mettre à jour les informations d'organisateur
     */
    public function updateOrganizerInfo(Organisateur $organisateur, array $data): Organisateur
    {
        $organisateur->update($data);
        return $organisateur;
    }

    /**
     * Obtenir tous les organisateurs
     */
    public function getAllOrganizers()
    {
        return Organisateur::with('utilisateur')->get();
    }
}
