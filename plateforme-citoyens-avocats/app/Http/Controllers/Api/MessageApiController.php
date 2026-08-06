<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Demande;
use App\Models\Avocat;
use App\Models\Citoyen;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageApiController extends Controller
{
    public function threads()
    {
        $user = Auth::user();
        $threads = [];

        if ($user->role === 'citoyen') {
            $dossiers = $user->citoyen->dossiers()->get();

            foreach ($dossiers as $dossier) {

                // Dossier clôturé : la conversation ne doit plus apparaître dans la liste
                if ($dossier->statut_dossier === 'cloture') {
                    continue;
                }

                $demande = Demande::where('id_dossier', $dossier->id_dossier)
                    ->where('statut_demande', 'acceptee')
                    ->first();

                if (!$demande) {
                    continue;
                }

                $avocat = Avocat::with('utilisateur')
                    ->find($demande->id_avocat);

                if (!$avocat) {
                    continue;
                }

                $dernier = Message::where('id_dossier', $dossier->id_dossier)
                    ->latest('heure')
                    ->first();

                $threads[] = [
                    'id_dossier' => $dossier->id_dossier,
                    'nom' => $avocat->utilisateur->nom,
                    'prenom' => $avocat->utilisateur->prenom,
                    'dernier_message' => $dernier?->contenu ?? ($dernier?->nom_fichier ? '📎 ' . $dernier->nom_fichier : null),
                    'heure' => $dernier?->heure,
                    'statut_dossier' => $dossier->statut_dossier
                ];
            }
        }

        if ($user->role === 'avocat') {
            $demandes = $user->avocat->demandes()
                ->where('statut_demande', 'acceptee')
                ->get();

            foreach ($demandes as $demande) {

                if (!$demande->dossier) {
                    continue;
                }

                // Dossier clôturé : la conversation ne doit plus apparaître dans la liste
                if ($demande->dossier->statut_dossier === 'cloture') {
                    continue;
                }

                $citoyen = Citoyen::with('utilisateur')
                    ->find($demande->dossier->id_citoyen);

                if (!$citoyen) {
                    continue;
                }

                $dernier = Message::where('id_dossier', $demande->id_dossier)
                    ->latest('heure')
                    ->first();

                $threads[] = [
                    'id_dossier' => $demande->id_dossier,
                    'nom' => $citoyen->utilisateur->nom,
                    'prenom' => $citoyen->utilisateur->prenom,
                    'dernier_message' => $dernier?->contenu ?? ($dernier?->nom_fichier ? '📎 ' . $dernier->nom_fichier : null),
                    'heure' => $dernier?->heure,
                    'statut_dossier' => $demande->dossier->statut_dossier
                ];
            }
        }

        return response()->json($threads);
    }

    public function show($idDossier)
    {
        $messages = Message::where('id_dossier', $idDossier)
            ->orderBy('heure')
            ->get();

        $dossier = Dossier::with([
            'citoyen.utilisateur',
            'demandes' => function ($q) {
                $q->where('statut_demande', 'acceptee');
            },
            'demandes.avocat.utilisateur'
        ])->findOrFail($idDossier);

        $user = Auth::user();

        if ($user->role === 'avocat') {

            if (!$dossier->citoyen || !$dossier->citoyen->utilisateur) {
                return response()->json([
                    'message' => 'Citoyen introuvable.'
                ], 404);
            }

            $correspondant = $dossier->citoyen->utilisateur;

        } else {

            $demandeAcceptee = $dossier->demandes->first();

            if (!$demandeAcceptee || !$demandeAcceptee->avocat || !$demandeAcceptee->avocat->utilisateur) {
                return response()->json([
                    'message' => 'Aucun avocat accepté pour ce dossier.'
                ], 404);
            }

            $correspondant = $demandeAcceptee->avocat->utilisateur;
        }

        return response()->json([
            'messages' => $messages,
            'correspondant' => $correspondant->prenom . ' ' . $correspondant->nom,
            'statut_dossier' => $dossier->statut_dossier,
            'receveur' => [
                'id_utilisateur' => $correspondant->id_utilisateur,
                'nom' => $correspondant->nom,
                'prenom' => $correspondant->prenom
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dossier' => 'required|exists:dossiers,id_dossier',
            'id_receveur' => 'required|exists:utilisateurs,id_utilisateur',
            'contenu' => 'required|string'
        ]);

        $dossier = Dossier::findOrFail($request->id_dossier);

        if ($dossier->statut_dossier === 'cloture') {
            return response()->json([
                'message' => 'Ce dossier est clôturé, la conversation est fermée.'
            ], 403);
        }

        $message = Message::create([
            'id_dossier' => $request->id_dossier,
            'id_expediteur' => Auth::user()->id_utilisateur,
            'id_receveur' => $request->id_receveur,
            'contenu' => $request->contenu,
            'heure' => now(),
            'statut_message' => 'non_lu'
        ]);

        return response()->json($message, 201);
    }

    public function storeFile(Request $request)
    {
        $request->validate([
            'id_dossier' => 'required|exists:dossiers,id_dossier',
            'id_receveur' => 'required|exists:utilisateurs,id_utilisateur',
            'fichier' => 'required|file|max:10240'
        ]);

        $dossier = Dossier::findOrFail($request->id_dossier);

        if ($dossier->statut_dossier === 'cloture') {
            return response()->json([
                'message' => 'Ce dossier est clôturé, la conversation est fermée.'
            ], 403);
        }

        $fichier = $request->file('fichier');
        $chemin = $fichier->store('messages', 'public');

        $message = Message::create([
            'id_dossier' => $request->id_dossier,
            'id_expediteur' => Auth::user()->id_utilisateur,
            'id_receveur' => $request->id_receveur,
            'contenu' => null,
            'heure' => now(),
            'statut_message' => 'non_lu',
            'chemin_fichier' => $chemin,
            'nom_fichier' => $fichier->getClientOriginalName(),
            'type_fichier' => $fichier->getClientMimeType(),
            'taille_fichier' => $fichier->getSize()
        ]);

        return response()->json($message, 201);
    }
}