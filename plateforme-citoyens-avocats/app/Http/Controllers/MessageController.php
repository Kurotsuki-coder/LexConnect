<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Demande;
use App\Models\Avocat;
use App\Models\Citoyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Liste des discussions ouvertes (demande acceptée), façon WhatsApp
    public function index()
    {
        $user = Auth::user();
        $correspondants = collect();

        if ($user->role === 'citoyen') {
            $dossierIds = $user->citoyen->dossiers()->pluck('id_dossier');
            $avocatIds = Demande::whereIn('id_dossier', $dossierIds)
                ->where('statut_demande', 'acceptee')
                ->pluck('id_avocat')->unique();
            $correspondants = Avocat::with('utilisateur')->whereIn('id_avocat', $avocatIds)->get()
                ->pluck('utilisateur');
        } elseif ($user->role === 'avocat') {
            $citoyenIds = Demande::where('id_avocat', $user->avocat->id_avocat)
                ->where('statut_demande', 'acceptee')
                ->with('dossier')->get()
                ->pluck('dossier.id_citoyen')->unique();
            $correspondants = Citoyen::with('utilisateur')->whereIn('id_citoyen', $citoyenIds)->get()
                ->pluck('utilisateur');
        }

        $threads = $correspondants->map(function ($correspondant) use ($user) {
            $dernierMessage = Message::where(function ($q) use ($user, $correspondant) {
                    $q->where('id_expediteur', $user->id_utilisateur)->where('id_receveur', $correspondant->id_utilisateur);
                })
                ->orWhere(function ($q) use ($user, $correspondant) {
                    $q->where('id_expediteur', $correspondant->id_utilisateur)->where('id_receveur', $user->id_utilisateur);
                })
                ->orderByDesc('heure')->first();

            return [
                'correspondant' => $correspondant,
                'dernier_message' => $dernierMessage,
            ];
        })->sortByDesc(fn($t) => $t['dernier_message']->heure ?? null);

        return view('messagerie.threads', compact('threads'));
    }

    // Ouvrir une discussion précise
    public function show($idUtilisateur)
    {
        $userId = Auth::user()->id_utilisateur;

        $messages = Message::where(function ($q) use ($userId, $idUtilisateur) {
                $q->where('id_expediteur', $userId)->where('id_receveur', $idUtilisateur);
            })
            ->orWhere(function ($q) use ($userId, $idUtilisateur) {
                $q->where('id_expediteur', $idUtilisateur)->where('id_receveur', $userId);
            })
            ->orderBy('heure')->get();

        $correspondant = \App\Models\Utilisateur::findOrFail($idUtilisateur);

        return view('messagerie.show', compact('messages', 'correspondant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_receveur' => 'required|exists:utilisateurs,id_utilisateur',
            'contenu' => 'required|string',
        ]);

        Message::create([
            'contenu' => $request->contenu,
            'id_expediteur' => Auth::user()->id_utilisateur,
            'id_receveur' => $request->id_receveur,
            'statut_message' => 'non_lu',
        ]);

        return back();
    }

    public function destroy(Message $message)
    {
        if ($message->id_expediteur !== Auth::user()->id_utilisateur) {
            abort(403);
        }
        $message->delete();
        return back();
    }
}