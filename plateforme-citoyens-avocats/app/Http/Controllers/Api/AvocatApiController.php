<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Dossier;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvocatApiController extends Controller
{
    public function profil()
    {
        $avocat=Auth::user()->avocat()->with('profil')->first();
        return response()->json($avocat);
    }


    public function updateProfil(Request $request)
    {
        $request->validate([
            'specialites'=>'nullable|string|max:255',
            'bio'=>'nullable|string',
            'disponibilite'=>'required|in:disponible,en_pause,en_vacances',
            'horaire'=>'nullable|string|max:255',
            'numero_barre'=>'nullable|string|max:50',
        ]);

        $avocat=Auth::user()->avocat;

        $profil=$avocat->profil()->updateOrCreate(
            ['id_avocat'=>$avocat->id_avocat],
            $request->only(
                'specialites',
                'bio',
                'disponibilite',
                'horaire',
                'numero_barre'
            )
        );

        return response()->json($profil);
    }


    // Affiche uniquement les demandes envoyées par les citoyens
    public function demandes()
    {
        $demandes=Auth::user()->avocat->demandes()
            ->where('origine','citoyen')
            ->with([
                'dossier:id_dossier,id_citoyen,motif,description,budget,statut_dossier,niveau_urgence',
                'dossier.citoyen.utilisateur'
            ])
            ->latest('heure')
            ->get();

        return response()->json($demandes);
    }


    // L'avocat accepte la demande d'un citoyen
    public function accepter(Demande $demande)
    {
        $this->authorizeAvocat($demande);

        if($demande->origine!=='citoyen'){
            abort(422,"Cette action concerne uniquement les demandes des citoyens.");
        }

        $demande->update([
            'statut_demande'=>'acceptee'
        ]);

        $demande->dossier->update([
            'statut_dossier'=>'en_cours'
        ]);

        return response()->json($demande);
    }


    // L'avocat refuse la demande d'un citoyen
    public function refuser(Demande $demande)
    {
        $this->authorizeAvocat($demande);

        if($demande->origine!=='citoyen'){
            abort(422,"Cette action concerne uniquement les demandes des citoyens.");
        }

        $demande->update([
            'statut_demande'=>'refusee'
        ]);

        return response()->json($demande);
    }


    public function resoudreDossier(Dossier $dossier)
    {
        $estMonDossier=Demande::where('id_dossier',$dossier->id_dossier)
            ->where('id_avocat',Auth::user()->avocat->id_avocat)
            ->where('origine','citoyen')
            ->where('statut_demande','acceptee')
            ->exists();

        if(!$estMonDossier){
            abort(403);
        }

        $dossier->update([
            'statut_dossier'=>'cloture'
        ]);

        return response()->json($dossier);
    }


    public function stats()
    {
        $avocat=Auth::user()->avocat;

        return response()->json([

            'demandes_en_attente'=>$avocat->demandes()
                ->where('origine','citoyen')
                ->where('statut_demande','envoyee')
                ->count(),

            'dossiers_en_cours'=>Dossier::whereIn(
                'id_dossier',
                $avocat->demandes()
                    ->where('origine','citoyen')
                    ->where('statut_demande','acceptee')
                    ->pluck('id_dossier')
            )
            ->where('statut_dossier','en_cours')
            ->count(),

            'dossiers_resolus'=>Dossier::whereIn(
                'id_dossier',
                $avocat->demandes()
                    ->where('origine','citoyen')
                    ->where('statut_demande','acceptee')
                    ->pluck('id_dossier')
            )
            ->where('statut_dossier','cloture')
            ->count(),

            'note_moyenne'=>round(
                Avis::where('id_avocat',$avocat->id_avocat)->avg('note')??0,
                1
            ),

            'nombre_avis'=>Avis::where('id_avocat',$avocat->id_avocat)->count()
        ]);
    }


    private function authorizeAvocat(Demande $demande):void
    {
        if($demande->id_avocat!==Auth::user()->avocat->id_avocat){
            abort(403);
        }
    }
}