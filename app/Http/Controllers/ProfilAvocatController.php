<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilAvocatController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'specialites' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'disponibilite' => 'required|in:disponible,en_pause,en_vacances',
            'horaire' => 'nullable|string|max:255',
            'numero_barre' => 'nullable|string|max:50',
        ]);

        $avocat = Auth::user()->avocat;

        $avocat->profil()->updateOrCreate(
            ['id_avocat' => $avocat->id_avocat],
            $request->only('specialites', 'bio', 'disponibilite', 'horaire', 'numero_barre')
        );

        return back()->with('success', 'Profil mis à jour.');
    }
}