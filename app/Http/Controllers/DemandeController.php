<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_dossier' => 'required|exists:dossiers,id_dossier',
            'id_avocat' => 'required|exists:avocats,id_avocat',
            'message' => 'nullable|string',
        ]);

        $dossier = Dossier::findOrFail($request->id_dossier);
        if ($dossier->id_citoyen !== Auth::user()->citoyen->id_citoyen) {
            abort(403);
        }

        Demande::create([
            'id_dossier' => $request->id_dossier,
            'id_avocat' => $request->id_avocat,
            'message' => $request->message,
            'statut_demande' => 'envoyee',
        ]);

        return back()->with('success', 'Demande envoyée.');
    }

    public function accepter(Demande $demande)
    {
        $this->authorizeAvocat($demande);
        $demande->update(['statut_demande' => 'acceptee']);
        return back()->with('success', 'Demande acceptée.');
    }

    public function refuser(Demande $demande)
    {
        $this->authorizeAvocat($demande);
        $demande->update(['statut_demande' => 'refusee']);
        return back()->with('success', 'Demande refusée.');
    }

    private function authorizeAvocat(Demande $demande): void
    {
        if ($demande->id_avocat !== Auth::user()->avocat->id_avocat) {
            abort(403);
        }
    }
}