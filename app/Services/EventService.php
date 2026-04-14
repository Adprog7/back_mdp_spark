<?php

namespace App\Services;

use App\Models\Evenement;
use App\Models\Organisateur;

class EventService
{
    /**
     * US 8 : Parcourir la liste des événements disponibles
     * En tant qu'utilisateur, je veux parcourir la liste des événements disponibles afin de trouver ceux qui m'intéressent.
     */
    public function getAvailableEvents($limit = 20, $offset = 0)
    {
        return Evenement::where('statut', 'actif')
            ->orderBy('date_debut', 'asc')
            ->limit($limit)
            ->offset($offset)
            ->with('organisateur')
            ->get();
    }

    /**
     * US 12 : Créer un événement
     * En tant qu'organisateur, je veux créer un événement (titre, date, lieu, capacité) afin de le rendre visible aux utilisateurs.
     */
    public function createEvent(Organisateur $organisateur, array $data): Evenement
    {
        return Evenement::create([
            'titre' => $data['titre'],
            'description' => $data['description'] ?? null,
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'lieu' => $data['lieu'],
            'capacite' => $data['capacite'] ?? null,
            'statut' => $data['statut'] ?? 'actif',
            'empreinte_carbonne' => $data['empreinte_carbonne'] ?? null,
            'id_organisateur' => $organisateur->id_organisateur,
        ]);
    }

    /**
     * US 13 : Modifier les détails d'un événement
     * En tant qu'organisateur, je veux modifier les détails de mon événement afin de mettre à jour les informations en cas de changement.
     */
    public function updateEvent(Evenement $evenement, array $data): Evenement
    {
        $evenement->update($data);
        return $evenement;
    }

    /**
     * US 14 : Consulter les statistiques d'un événement
     * En tant qu'organisateur, je veux consulter les statistiques (suivi) de mon événement afin de connaître le taux de remplissage.
     */
    public function getEventStats(Evenement $evenement): array
    {
        $totalBillets = $evenement->billets()->count();
        $billetsPayes = $evenement->billets()->where('statut', 'paye')->count();
        $billetsEnAttente = $evenement->billets()->where('statut', 'en_attente')->count();
        $capacite = $evenement->capacite ?? 0;

        return [
            'total_billets' => $totalBillets,
            'billets_payes' => $billetsPayes,
            'billets_en_attente' => $billetsEnAttente,
            'capacite' => $capacite,
            'taux_remplissage' => $capacite > 0 ? round(($totalBillets / $capacite) * 100, 2) : 0,
            'places_restantes' => max(0, $capacite - $totalBillets),
        ];
    }

    /**
     * Obtenir un événement par ID
     */
    public function getEventById(int $eventId): ?Evenement
    {
        return Evenement::with('organisateur', 'billets')->find($eventId);
    }

    /**
     * Obtenir les événements d'un organisateur
     */
    public function getOrganizerEvents(Organisateur $organisateur)
    {
        return Evenement::where('id_organisateur', $organisateur->id_organisateur)
            ->orderBy('date_debut', 'desc')
            ->get();
    }

    /**
     * Rechercher des événements
     */
    public function searchEvents(string $query)
    {
        return Evenement::where('titre', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('lieu', 'LIKE', "%{$query}%")
            ->where('statut', 'actif')
            ->get();
    }

    /**
     * Supprimer un événement
     */
    public function deleteEvent(Evenement $evenement): bool
    {
        return $evenement->delete();
    }

    /**
     * Obtenir les événements à venir
     */
    public function getUpcomingEvents($days = 30)
    {
        $now = now();
        $futureDate = $now->copy()->addDays($days);

        return Evenement::whereBetween('date_debut', [$now, $futureDate])
            ->where('statut', 'actif')
            ->orderBy('date_debut', 'asc')
            ->get();
    }
}
