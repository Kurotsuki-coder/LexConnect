<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Demande;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisApiController extends Controller
{
    public function index($idAvocat)
    {
        $avis = Avis::where('id_avocat', $idAvocat)
            ->with('citoyen.utilisateur')
            ->latest('id_avis')
            ->get()
            ->map(function ($a) {
                return [
                    'id_avis' => $a->id_avis,
                    'note' => $a->note,
                    'commentaire' => $a->commentaire,
                    'date' => $a->date,
                    'citoyen' => $a->citoyen && $a->citoyen->utilisateur
                        ? $a->citoyen->utilisateur->prenom . ' ' . substr($a->citoyen->utilisateur->nom, 0, 1) . '.'
                        : 'Citoyen',
                ];
            });

        return response()->json($avis);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dossier' => 'required|exists:dossiers,id_dossier',
            'note' => 'required|integer|between:1,5',
            'commentaire' => 'nullable|string',
        ]);

        $dossier = Dossier::findOrFail($request->id_dossier);

        if ($dossier->id_citoyen !== Auth::user()->citoyen->id_citoyen) {
            abort(403);
        }
        if ($dossier->statut_dossier !== 'cloture') {
            abort(422, 'Ce dossier n\'est pas encore clôturé.');
        }

        $demande = Demande::where('id_dossier', $dossier->id_dossier)
            ->where('statut_demande', 'acceptee')
            ->first();

        if (!$demande) {
            abort(422, 'Aucun avocat associé à ce dossier.');
        }

        $avis = Avis::create([
            'id_citoyen' => Auth::user()->citoyen->id_citoyen,
            'id_avocat' => $demande->id_avocat,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
        ]);

        return response()->json($avis, 201);
    }
}