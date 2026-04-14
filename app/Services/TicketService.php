<?php

namespace App\Services;

use App\Models\Billet;
use App\Models\Evenement;
use App\Models\Utilisateur;

class TicketService
{
    /**
     * US 9 : Sélectionner un billet pour un événement
     * En tant qu'utilisateur, je veux sélectionner un billet pour un événement afin de lancer le processus d'achat.
     */
    public function reserveTicket(Utilisateur $utilisateur, Evenement $evenement, array $data): Billet
    {
        return Billet::create([
            'prix' => $data['prix'] ?? 0,
            'statut' => 'en_attente',
            'id_utilisateur' => $utilisateur->id_utilisateur,
            'id_evenement' => $evenement->id_evenement,
        ]);
    }

    /**
     * Obtenir les billets d'un utilisateur
     */
    public function getUserTickets(Utilisateur $utilisateur)
    {
        return Billet::where('id_utilisateur', $utilisateur->id_utilisateur)
            ->with('evenement', 'evenement.organisateur')
            ->orderBy('date_achat', 'desc')
            ->get();
    }

    /**
     * Obtenir les billets d'un événement
     */
    public function getEventTickets(Evenement $evenement)
    {
        return Billet::where('id_evenement', $evenement->id_evenement)
            ->with('utilisateur')
            ->get();
    }

    /**
     * Obtenir un billet par ID
     */
    public function getTicketById(int $ticketId): ?Billet
    {
        return Billet::find($ticketId);
    }

    /**
     * Vérifier si un utilisateur a déjà un billet pour un événement
     */
    public function hasTicketForEvent(Utilisateur $utilisateur, Evenement $evenement): bool
    {
        return Billet::where('id_utilisateur', $utilisateur->id_utilisateur)
            ->where('id_evenement', $evenement->id_evenement)
            ->exists();
    }

    /**
     * Mettre à jour le statut d'un billet
     */
    public function updateTicketStatus(Billet $billet, string $status): Billet
    {
        $billet->update(['statut' => $status]);
        return $billet;
    }

    /**
     * Annuler un billet
     */
    public function cancelTicket(Billet $billet): bool
    {
        return $this->updateTicketStatus($billet, 'annule')->save();
    }

    /**
     * Obtenir le prix d'un billet pour un événement
     */
    public function getTicketPrice(Evenement $evenement): float
    {
        // À implémenter selon la logique métier
        return 0;
    }

    /**
     * Vérifier la disponibilité des billets
     */
    public function isTicketAvailable(Evenement $evenement): bool
    {
        if (!$evenement->capacite) {
            return true;
        }

        $billetsVendus = Billet::where('id_evenement', $evenement->id_evenement)
            ->where('statut', '!=', 'annule')
            ->count();

        return $billetsVendus < $evenement->capacite;
    }
}
