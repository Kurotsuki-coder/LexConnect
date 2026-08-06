<?php

namespace App\Http\Controllers;

use App\Models\Avocat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RechercheAvocatController extends Controller
{
    public function index(Request $request)
    {
        $query = Avocat::with(['utilisateur', 'profil'])
            ->whereHas('utilisateur', fn($q) => $q->where('statut', 'actif'));

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('utilisateur', function ($q) use ($search) {
                $q->where('nom', 'ilike', "%{$search}%")
                  ->orWhere('prenom', 'ilike', "%{$search}%");
            });
        }

        $avocats = $query->get();
        $dossiers = Auth::user()->citoyen->dossiers()->where('statut_dossier', '!=', 'cloture')->get();

        return view('citoyen.avocats.index', compact('avocats', 'dossiers'));
    }
}