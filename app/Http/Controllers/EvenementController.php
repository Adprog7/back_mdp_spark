<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Crée un nouvel événement.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('affiche')) {
            $path = $request->file('affiche')->store('evenements', 'public');
            $validated['affiche'] = $path;
        }

        // L'utilisateur connecté doit avoir un compte organisateur associé
        $validated['id_organisateur'] = $request->user()->organisateur->id_organisateur;
        $validated['statut'] = 'En cours';
        $validated['empreinte_carbonne'] = 0;

        $event = Evenement::create($validated);

        return response()->json($this->format($event), 201);
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
            'image'       => $event->affiche ? url('storage/' . $event->affiche) : null,
            'price'       => null,
            'countryFlag' => '🇫🇷',
        ];
    }
}
