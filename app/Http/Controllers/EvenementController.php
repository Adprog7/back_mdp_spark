<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class EvenementController extends Controller
{
    /**
     * Retourne tous les événements de la base de données.
     */
    public function index(): JsonResponse
    {
        $evenements = Evenement::all();

        $data = $evenements->map(fn ($event) => $this->format($event));

        return response()->json($data);
    }

    /**
     * Retourne un seul événement par son id.
     */
    public function show(int $id): JsonResponse
    {
        $event = Evenement::where('id_evenement', $id)->firstOrFail();

        return response()->json($this->format($event));
    }

    /**
     * Transforme un Evenement en tableau compatible avec le front-end.
     * Les champs absents de la BDD (theme, image, price, countryFlag)
     * sont ignorés pour l'instant.
     */
    private function format(Evenement $event): array
    {
        Carbon::setLocale('fr');
        $dateDebut = Carbon::parse($event->date_debut);

        return [
            'id'          => $event->id_evenement,
            'title'       => $event->titre,
            'description' => $event->description,
            'city'        => $event->lieu,
            'date'        => $dateDebut->isoFormat('D MMMM YYYY'),
            'time'        => $dateDebut->format('H:i'),
            'capacite'    => $event->capacite,
            'statut'      => $event->statut,
            // Champs non présents dans la BDD – laissés vides pour l'instant
            'theme'       => null,
            'image'       => null,
            'price'       => null,
            'countryFlag' => '🇫🇷',
        ];
    }
}
