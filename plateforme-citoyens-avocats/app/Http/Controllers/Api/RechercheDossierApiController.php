<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Http\Request;

class RechercheDossierApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Dossier::with('citoyen.utilisateur')
            ->where('statut_dossier', 'ouvert');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('motif', 'ilike', "%{$search}%");
        }

        if ($request->filled('urgence')) {
            $query->where('niveau_urgence', $request->urgence);
        }

        return response()->json($query->latest()->get());
    }
}