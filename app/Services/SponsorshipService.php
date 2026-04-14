<?php

namespace App\Services;

use App\Models\Sponsorisation;
use App\Models\Evenement;
use App\Models\Partenaire;

class SponsorshipService
{
    /**
     * US 15 : Associer des sponsors à un événement
     * En tant qu'organisateur, je veux associer des sponsors à mon événement afin de gérer les partenariats financiers.
     */
    public function addSponsor(Evenement $evenement, Partenaire $partenaire, array $data): Sponsorisation
    {
        return Sponsorisation::create([
            'montant' => $data['montant'] ?? null,
            'type_visibilite' => $data['type_visibilite'] ?? 'standard',
            'id_evenement' => $evenement->id_evenement,
            'id_partenaire' => $partenaire->id_partenaire,
        ]);
    }

    /**
     * Obtenir tous les sponsors d'un événement
     */
    public function getEventSponsors(Evenement $evenement)
    {
        return Sponsorisation::where('id_evenement', $evenement->id_evenement)
            ->with('partenaire')
            ->get();
    }

    /**
     * Obtenir tous les événements sponsorisés par un partenaire
     */
    public function getPartnerEvents(Partenaire $partenaire)
    {
        return Sponsorisation::where('id_partenaire', $partenaire->id_partenaire)
            ->with('evenement')
            ->get();
    }

    /**
     * Mettre à jour les détails d'une sponsorisation
     */
    public function updateSponsorship(Sponsorisation $sponsorisation, array $data): Sponsorisation
    {
        $sponsorisation->update($data);
        return $sponsorisation;
    }

    /**
     * Supprimer une sponsorisation
     */
    public function removeSponsorship(Sponsorisation $sponsorisation): bool
    {
        return $sponsorisation->delete();
    }

    /**
     * Calculer le montant total des sponsorisations d'un événement
     */
    public function getTotalSponsorshipAmount(Evenement $evenement): float
    {
        return Sponsorisation::where('id_evenement', $evenement->id_evenement)
            ->sum('montant') ?? 0;
    }

    /**
     * Obtenir les sponsors par type de visibilité
     */
    public function getSponsorsByVisibility(Evenement $evenement, string $visibility)
    {
        return Sponsorisation::where('id_evenement', $evenement->id_evenement)
            ->where('type_visibilite', $visibility)
            ->with('partenaire')
            ->get();
    }

    /**
     * Créer un nouveau partenaire
     */
    public function createPartner(array $data): Partenaire
    {
        return Partenaire::create([
            'nom' => $data['nom'],
            'secteur' => $data['secteur'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Obtenir un partenaire par ID
     */
    public function getPartnerById(int $partnerId): ?Partenaire
    {
        return Partenaire::find($partnerId);
    }

    /**
     * Obtenir tous les partenaires
     */
    public function getAllPartners()
    {
        return Partenaire::all();
    }

    /**
     * Mettre à jour les informations d'un partenaire
     */
    public function updatePartner(Partenaire $partenaire, array $data): Partenaire
    {
        $partenaire->update($data);
        return $partenaire;
    }
}
