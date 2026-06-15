<?php

namespace App\Http\Controllers;

use App\Models\Ami;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AmiController extends Controller
{
    /**
     * Recherche des utilisateurs par nom ou email.
     * GET /api/utilisateurs?search=marie
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->query('search', '');

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->select('id', 'name', 'email')
            ->limit(20)
            ->get();

        // Pour chaque utilisateur, on indique l'état de la relation avec l'utilisateur connecté
        $userId = Auth::id();
        $data = $users->map(function (User $user) use ($userId) {
            $relation = Ami::where(function ($q) use ($userId, $user) {
                $q->where('id_demandeur', $userId)->where('id_destinataire', $user->id);
            })->orWhere(function ($q) use ($userId, $user) {
                $q->where('id_demandeur', $user->id)->where('id_destinataire', $userId);
            })->first();

            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'username'     => strtolower(str_replace(' ', '_', $user->name)),
                'statut'       => $relation?->statut ?? null,       // null = pas de relation
                'isFriend'     => $relation?->statut === 'accepte',
                'requestSent'  => $relation?->statut === 'en_attente' && $relation?->id_demandeur === $userId,
            ];
        });

        return response()->json($data);
    }

    /**
     * Retourne la liste des amis acceptés de l'utilisateur connecté.
     * GET /api/amis
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();

        $amis = Ami::with(['demandeur', 'destinataire'])
            ->where('statut', 'accepte')
            ->where(function ($q) use ($userId) {
                $q->where('id_demandeur', $userId)
                  ->orWhere('id_destinataire', $userId);
            })
            ->get()
            ->map(function (Ami $ami) use ($userId) {
                // L'ami est l'autre côté de la relation
                $ami_user = $ami->id_demandeur === $userId
                    ? $ami->destinataire
                    : $ami->demandeur;

                return [
                    'id'       => $ami_user->id,
                    'name'     => $ami_user->name,
                    'username' => strtolower(str_replace(' ', '_', $ami_user->name)),
                    'amiId'    => $ami->id,   // utile pour la suppression
                ];
            });

        return response()->json($amis);
    }

    /**
     * Envoie une demande d'amitié.
     * POST /api/amis
     * Body JSON : { "id_destinataire": 5 }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_destinataire' => 'required|integer|exists:users,id',
        ]);

        $userId       = Auth::id();
        $idDestinataire = $request->integer('id_destinataire');

        // Empêche de s'ajouter soi-même
        if ($userId === $idDestinataire) {
            return response()->json(['message' => 'Vous ne pouvez pas vous ajouter vous-même.'], 422);
        }

        // Vérifie qu'une relation n'existe pas déjà
        $existante = Ami::where(function ($q) use ($userId, $idDestinataire) {
            $q->where('id_demandeur', $userId)->where('id_destinataire', $idDestinataire);
        })->orWhere(function ($q) use ($userId, $idDestinataire) {
            $q->where('id_demandeur', $idDestinataire)->where('id_destinataire', $userId);
        })->first();

        if ($existante) {
            return response()->json(['message' => 'Une relation existe déjà avec cet utilisateur.'], 409);
        }

        $ami = Ami::create([
            'id_demandeur'    => $userId,
            'id_destinataire' => $idDestinataire,
            'statut'          => 'en_attente',
        ]);

        return response()->json([
            'message' => 'Demande d\'amitié envoyée.',
            'ami'     => $ami,
        ], 201);
    }

    /**
     * Accepte ou refuse une demande d'amitié reçue.
     * PATCH /api/amis/{id}
     * Body JSON : { "statut": "accepte" | "refuse" }
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'statut' => 'required|in:accepte,refuse',
        ]);

        $ami = Ami::findOrFail($id);

        // Seul le destinataire peut accepter/refuser
        if ($ami->id_destinataire !== Auth::id()) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $ami->update(['statut' => $request->statut]);

        return response()->json([
            'message' => $request->statut === 'accepte' ? 'Ami ajouté !' : 'Demande refusée.',
            'ami'     => $ami,
        ]);
    }

    /**
     * Supprime une relation d'amitié (retirer un ami ou annuler une demande).
     * DELETE /api/amis/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $userId = Auth::id();
        $ami    = Ami::findOrFail($id);

        // Seul l'un des deux utilisateurs de la relation peut supprimer
        if ($ami->id_demandeur !== $userId && $ami->id_destinataire !== $userId) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $ami->delete();

        return response()->json(['message' => 'Relation supprimée.']);
    }
}
