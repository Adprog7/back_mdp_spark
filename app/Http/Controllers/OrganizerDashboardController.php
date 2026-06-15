<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evenement;
use App\Models\Billet;

class OrganizerDashboardController extends Controller
{
    public function getStats($id)
    {
        // Récupérer tous les événements de l'organisateur
        $evenements = Evenement::where('id_organisateur', $id)->get();
        $evenementsIds = $evenements->pluck('id_evenement');

        // Chiffre d'affaires : somme du prix des billets vendus pour ces événements
        // On considère que si le billet existe, il est vendu (ou on pourrait vérifier le statut si besoin, par exemple statut='payé', mais prenons la somme pour l'instant)
        $revenue = Billet::whereIn('id_evenement', $evenementsIds)->sum('prix');

        // Nombre de billets vendus
        $ticketsSold = Billet::whereIn('id_evenement', $evenementsIds)->count();

        // Engagement : (total billets vendus / capacité totale) * 100
        $totalCapacity = $evenements->sum('capacite');
        $engagement = $totalCapacity > 0 ? round(($ticketsSold / $totalCapacity) * 100, 1) : 0;

        // Tendances simulées pour l'UI (pourrait être calculé par rapport au mois précédent)
        return response()->json([
            'revenue' => [
                'value' => number_format($revenue, 2, ',', ' ') . '€',
                'trend' => '+12.5%'
            ],
            'tickets' => [
                'value' => $ticketsSold,
                'trend' => '+5.2%'
            ],
            'engagement' => [
                'value' => $engagement . '%',
                'trend' => '+18.7%'
            ]
        ]);
    }

    public function getEvents($id)
    {
        // Récupérer les événements avec le nombre de billets vendus
        $evenements = Evenement::where('id_organisateur', $id)
            ->withCount('billets as sold')
            ->orderBy('date_debut', 'asc')
            ->get();

        $formattedEvents = $evenements->map(function ($event) {
            return [
                'id' => $event->id_evenement,
                'title' => $event->titre,
                // Formatage simple de la date (pourrait utiliser Carbon)
                'date' => date('d M Y', strtotime($event->date_debut)),
                'sold' => $event->sold,
                'total' => $event->capacite,
                'status' => $event->statut ?? 'En cours'
            ];
        });

        return response()->json($formattedEvents);
    }
}
