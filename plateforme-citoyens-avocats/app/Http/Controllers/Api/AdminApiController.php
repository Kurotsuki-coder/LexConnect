<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AdminApiController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_citoyens'=>Utilisateur::where('role','citoyen')->count(),
            'total_avocats'=>Utilisateur::where('role','avocat')->where('statut','actif')->count(),
            'avocats_en_attente'=>Utilisateur::where('role','avocat')->where('statut','en_attente')->count(),
            'comptes_suspendus'=>Utilisateur::where('statut','suspendu')->count(),
        ]);
    }

    public function avocatsEnAttente()
    {
        return response()->json(
            Utilisateur::with('avocat.profil')
                ->where('role','avocat')
                ->where('statut','en_attente')
                ->latest()
                ->get()
        );
    }

    public function utilisateurs()
    {
        return response()->json(
            Utilisateur::with('avocat.profil')
                ->where('role','!=','admin')
                ->latest()
                ->get()
        );
    }

    public function validerAvocat(Utilisateur $user)
    {
        $user->update([
            'statut'=>'actif',
            'motif_statut'=>null
        ]);

        return response()->json($user);
    }

    public function refuserAvocat(Request $request,Utilisateur $user)
    {
        $request->validate([
            'motif'=>'required|string|max:500'
        ]);

        $user->update([
            'statut'=>'refuse',
            'motif_statut'=>$request->motif
        ]);

        return response()->json($user);
    }

    public function suspendreCompte(Request $request,Utilisateur $user)
    {
        $request->validate([
            'motif'=>'required|string|max:500'
        ]);

        $user->update([
            'statut'=>'suspendu',
            'motif_statut'=>$request->motif
        ]);

        return response()->json($user);
    }

    public function reactiverCompte(Utilisateur $user)
    {
        $user->update([
            'statut'=>'actif',
            'motif_statut'=>null
        ]);

        return response()->json($user);
    }

    public function supprimerCompte(Request $request,Utilisateur $user)
    {
        $request->validate([
            'motif'=>'required|string|max:500'
        ]);

        $user->update([
            'statut'=>'supprime',
            'motif_statut'=>$request->motif
        ]);

        return response()->json([
            'message'=>'Compte désactivé.'
        ]);
    }
}