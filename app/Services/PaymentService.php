<?php

namespace App\Services;

use App\Models\Billet;
use App\Models\Paiement;
use App\Models\Utilisateur;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * US 10 : Payer un billet en ligne via un système sécurisé
     * En tant qu'utilisateur, je veux payer mon billet en ligne via un système sécurisé afin de confirmer ma réservation.
     */
    public function processPayment(Billet $billet, array $data): Paiement
    {
        $paiement = Paiement::create([
            'montant' => $billet->prix,
            'moyen_paiement' => $data['moyen_paiement'] ?? 'carte',
            'statut' => 'en_cours',
            'id_billet' => $billet->id_billet,
        ]);

        // Intégration avec un fournisseur de paiement (Stripe, PayPal, etc.)
        // Cette logique dépendra du choix du fournisseur

        return $paiement;
    }

    /**
     * US 11 : Payer un billet pour un membre d'un cercle
     * En tant qu'utilisateur, je veux payer un billet pour un membre de mon cercle afin d'offrir une place ou de centraliser l'achat.
     */
    public function payTicketForMember(Utilisateur $payeur, Billet $billet, array $data): Paiement
    {
        // Vérifier que le payeur a les droits
        // Cette vérification dépendra de la logique métier

        $paiement = Paiement::create([
            'montant' => $billet->prix,
            'moyen_paiement' => $data['moyen_paiement'] ?? 'carte',
            'statut' => 'en_cours',
            'id_billet' => $billet->id_billet,
        ]);

        return $paiement;
    }

    /**
     * Confirmer un paiement
     */
    public function confirmPayment(Paiement $paiement): Paiement
    {
        $paiement->update(['statut' => 'confirme']);
        
        // Mettre à jour le statut du billet
        $paiement->billet->update(['statut' => 'paye']);

        return $paiement;
    }

    /**
     * Annuler un paiement
     */
    public function cancelPayment(Paiement $paiement): Paiement
    {
        $paiement->update(['statut' => 'annule']);
        
        // Mettre à jour le statut du billet
        $paiement->billet->update(['statut' => 'en_attente']);

        return $paiement;
    }

    /**
     * Obtenir les paiements d'un utilisateur
     */
    public function getUserPayments(Utilisateur $utilisateur)
    {
        return Paiement::whereHas('billet', function ($query) use ($utilisateur) {
            $query->where('id_utilisateur', $utilisateur->id_utilisateur);
        })->with('billet.evenement')->get();
    }

    /**
     * Obtenir un paiement par ID
     */
    public function getPaymentById(int $paymentId): ?Paiement
    {
        return Paiement::find($paymentId);
    }

    /**
     * Générer une référence de paiement unique
     */
    public function generatePaymentReference(): string
    {
        return 'PAY-' . Str::uuid();
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function getPaymentStatus(Paiement $paiement): string
    {
        return $paiement->statut;
    }

    /**
     * Obtenir l'historique de paiement d'un événement
     */
    public function getEventPaymentHistory(int $eventId)
    {
        return Paiement::whereHas('billet', function ($query) use ($eventId) {
            $query->where('id_evenement', $eventId);
        })->with('billet.utilisateur')->get();
    }

    /**
     * Calculer le revenu total d'un événement
     */
    public function getEventRevenue(int $eventId): float
    {
        return Paiement::whereHas('billet', function ($query) use ($eventId) {
            $query->where('id_evenement', $eventId);
        })->where('statut', 'confirme')->sum('montant');
    }
}
