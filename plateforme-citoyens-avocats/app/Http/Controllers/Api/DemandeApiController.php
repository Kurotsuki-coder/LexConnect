<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_dossier'=>'required|exists:dossiers,id_dossier',
            'id_avocat'=>'required|exists:avocats,id_avocat',
            'message'=>'nullable|string',
        ]);

        $dossier=Dossier::findOrFail($request->id_dossier);

        if($dossier->id_citoyen!==Auth::user()->citoyen->id_citoyen){
            abort(403);
        }

        // Une demande vers cet avocat existe déjà pour ce dossier et n'a pas été refusée
        $demandeExistante=Demande::where('id_dossier',$request->id_dossier)
            ->where('id_avocat',$request->id_avocat)
            ->where('origine','citoyen')
            ->whereIn('statut_demande',['envoyee','acceptee'])
            ->exists();

        if($demandeExistante){
            abort(422,'Vous avez déjà envoyé une demande à cet avocat pour ce dossier.');
        }

        $demande=Demande::create([
            'id_dossier'=>$request->id_dossier,
            'id_avocat'=>$request->id_avocat,
            'message'=>$request->message,
            'statut_demande'=>'envoyee',
            'heure'=>now(),
            'origine'=>'citoyen',
        ]);

        return response()->json($demande,201);
    }

    public function proposer(Request $request,Dossier $dossier)
    {
        $request->validate([
            'message'=>'nullable|string|max:1000',
        ]);

        if($dossier->statut_dossier!=='ouvert'){
            abort(422,"Ce dossier n'est plus disponible.");
        }

        $avocat=Auth::user()->avocat;

        $dejaPropose=Demande::where('id_dossier',$dossier->id_dossier)
            ->where('id_avocat',$avocat->id_avocat)
            ->where('origine','avocat')
            ->exists();

        if($dejaPropose){
            abort(422,'Tu as déjà proposé tes services sur ce dossier.');
        }

        $demande=Demande::create([
            'id_dossier'=>$dossier->id_dossier,
            'id_avocat'=>$avocat->id_avocat,
            'message'=>$request->message,
            'statut_demande'=>'envoyee',
            'heure'=>now(),
            'origine'=>'avocat',
        ]);

        return response()->json($demande,201);
    }

    public function accepterProposition(Demande $demande)
    {
        $this->authorizeCitoyen($demande);

        if($demande->origine!=='avocat'){
            abort(422,"Cette action concerne uniquement les propositions d'avocats.");
        }

        $demande->update([
            'statut_demande'=>'acceptee'
        ]);

        Demande::where('id_dossier',$demande->id_dossier)
            ->where('id_demande','!=',$demande->id_demande)
            ->where('origine','avocat')
            ->where('statut_demande','envoyee')
            ->update([
                'statut_demande'=>'refusee'
            ]);

        $demande->dossier->update([
            'statut_dossier'=>'en_cours'
        ]);

        return response()->json($demande->load('avocat.utilisateur'));
    }

    public function refuserProposition(Demande $demande)
    {
        $this->authorizeCitoyen($demande);

        if($demande->origine!=='avocat'){
            abort(422,"Cette action concerne uniquement les propositions d'avocats.");
        }

        $demande->update([
            'statut_demande'=>'refusee'
        ]);

        return response()->json($demande);
    }

    private function authorizeCitoyen(Demande $demande):void
    {
        if($demande->dossier->id_citoyen!==Auth::user()->citoyen->id_citoyen){
            abort(403);
        }
    }
}