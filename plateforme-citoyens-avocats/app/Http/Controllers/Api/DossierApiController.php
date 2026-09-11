<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Message;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DossierApiController extends Controller
{
    public function index()
    {
        $dossiers=Auth::user()->citoyen->dossiers()->latest('id_dossier')->get();
        return response()->json($dossiers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'motif'=>'required|string|max:255',
            'description'=>'nullable|string',
            'budget'=>'nullable|numeric',
            'niveau_urgence'=>'required|integer|between:1,4'
        ]);

        $dossier=Auth::user()->citoyen->dossiers()->create([
            'motif'=>$request->motif,
            'description'=>$request->description,
            'budget'=>$request->budget,
            'niveau_urgence'=>$request->niveau_urgence,
            'statut_dossier'=>'ouvert'
        ]);

        return response()->json($dossier,201);
    }
    
    public function show(Dossier $dossier)
    {
        $this->authorizeAccess($dossier);

        $dossier->load([
            'demandes'=>function($query){
                $query->where('origine','avocat')
                    ->with('avocat.utilisateur','avocat.profil')
                    ->latest('heure');
            }
        ]);

        return response()->json($dossier);
    }

    public function update(Request $request,Dossier $dossier)
    {
        $this->authorizeAccess($dossier);

        if($dossier->statut_dossier!=='ouvert'){
            return response()->json([
                'message'=>'Ce dossier ne peut plus être modifié.'
            ],422);
        }

        $request->validate([
            'motif'=>'required|string|max:255',
            'description'=>'nullable|string',
            'budget'=>'nullable|numeric',
            'niveau_urgence'=>'required|integer|between:1,4'
        ]);

        $dossier->update([
            'motif'=>$request->motif,
            'description'=>$request->description,
            'budget'=>$request->budget,
            'niveau_urgence'=>$request->niveau_urgence
        ]);

        return response()->json($dossier);
    }

    public function destroy(Dossier $dossier)
    {
        $this->authorizeAccess($dossier);

        if($dossier->statut_dossier!=='ouvert'){
            return response()->json([
                'message'=>'Ce dossier est déjà pris en charge.'
            ],422);
        }

        $dossier->delete();

        return response()->json([
            'message'=>'Dossier supprimé.'
        ]);
    }

    public function stats()
    {
        $user=Auth::user();
        $dossiers=$user->citoyen->dossiers();

        $messages=Message::where('id_receveur',$user->id_utilisateur)
            ->where('statut_message','non_lu')
            ->count();

        return response()->json([
            'actifs'=>(clone $dossiers)
                ->whereIn('statut_dossier',['ouvert','en_cours'])
                ->count(),

            'urgents'=>(clone $dossiers)
                ->whereIn('niveau_urgence',[3,4])
                ->whereIn('statut_dossier',['ouvert','en_cours'])
                ->count(),

            'demandes_acceptees'=>Demande::whereIn(
                'id_dossier',
                (clone $dossiers)->pluck('id_dossier')
            )
            ->where('origine','avocat')
            ->where('statut_demande','acceptee')
            ->count(),

            'messages'=>$messages
        ]);
    }

    private function authorizeAccess(Dossier $dossier):void
    {
        if($dossier->id_citoyen!==Auth::user()->citoyen->id_citoyen){
            abort(403);
        }
    }
}