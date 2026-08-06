<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;

class AdminController extends Controller
{
    public function index()
    {
        $avocatsEnAttente = Utilisateur::where('role', 'avocat')->where('statut', 'en_attente')->get();
        $utilisateurs = Utilisateur::where('role', '!=', 'admin')->latest()->get();

        return view('admin.dashboard', compact('avocatsEnAttente', 'utilisateurs'));
    }

    public function validerAvocat(Utilisateur $user)
    {
        $user->update(['statut' => 'actif']);
        return back()->with('success', 'Avocat validé.');
    }

    public function refuserAvocat(Utilisateur $user)
    {
        $user->update(['statut' => 'refuse']);
        return back()->with('success', 'Avocat refusé.');
    }

    public function suspendreCompte(Utilisateur $user)
    {
        $user->update(['statut' => 'suspendu']);
        return back()->with('success', 'Compte suspendu.');
    }

    public function reactiverCompte(Utilisateur $user)
    {
        $user->update(['statut' => 'actif']);
        return back()->with('success', 'Compte réactivé.');
    }

    public function supprimerCompte(Utilisateur $user)
    {
        $user->delete();
        return back()->with('success', 'Compte supprimé.');
    }
}