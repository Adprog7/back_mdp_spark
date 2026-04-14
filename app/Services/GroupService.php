<?php

namespace App\Services;

use App\Models\Groupe;
use App\Models\Utilisateur;
use Illuminate\Support\Str;

class GroupService
{
    /**
     * US 5 : Créer un cercle social
     * En tant qu'utilisateur, je veux créer un cercle social afin de regrouper mes amis pour des sorties communes.
     */
    public function createGroup(Utilisateur $utilisateur, array $data): Groupe
    {
        return Groupe::create([
            'nom' => $data['nom'],
            'code_invitation' => $this->generateInvitationCode(),
            'id_utilisateur_createur' => $utilisateur->id_utilisateur,
        ]);
    }

    /**
     * US 6 : Générer un lien ou un code d'invitation
     * En tant qu'administrateur d'un cercle, je veux générer un lien ou un code d'invitation afin de faciliter l'ajout de nouveaux membres.
     */
    public function generateInvitationCode(): string
    {
        return strtoupper(Str::random(8));
    }

    /**
     * Régénérer un code d'invitation
     */
    public function regenerateInvitationCode(Groupe $groupe): string
    {
        $nouveauCode = $this->generateInvitationCode();
        $groupe->update(['code_invitation' => $nouveauCode]);
        return $nouveauCode;
    }

    /**
     * US 7 : Rejoindre un cercle via un code
     * En tant qu'utilisateur, je veux rejoindre un cercle via un code afin de participer à l'organisation de groupe.
     */
    public function joinGroupByCode(string $invitationCode): ?Groupe
    {
        return Groupe::where('code_invitation', $invitationCode)->first();
    }

    /**
     * Obtenir tous les groupes d'un utilisateur
     */
    public function getUserGroups(Utilisateur $utilisateur)
    {
        return Groupe::where('id_utilisateur_createur', $utilisateur->id_utilisateur)->get();
    }

    /**
     * Obtenir un groupe par ID
     */
    public function getGroupById(int $groupId): ?Groupe
    {
        return Groupe::find($groupId);
    }

    /**
     * Supprimer un groupe
     */
    public function deleteGroup(Groupe $groupe): bool
    {
        return $groupe->delete();
    }

    /**
     * Mettre à jour les informations d'un groupe
     */
    public function updateGroup(Groupe $groupe, array $data): Groupe
    {
        $groupe->update($data);
        return $groupe;
    }

    /**
     * Vérifier si un utilisateur est propriétaire d'un groupe
     */
    public function isGroupOwner(Utilisateur $utilisateur, Groupe $groupe): bool
    {
        return $groupe->id_utilisateur_createur === $utilisateur->id_utilisateur;
    }
}
