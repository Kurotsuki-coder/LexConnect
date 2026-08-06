<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avocat;
use Illuminate\Http\Request;


class RechercheAvocatApiController extends Controller
{


    public function index(Request $request)
    {

        $query = Avocat::with([

            'utilisateur',

            'profil'

        ])
        ->withCount('avis')
        ->withAvg('avis','note');



        if($request->filled('q')){


            $q=$request->q;


            $query->whereHas('utilisateur',function($user) use($q){


                $user->where('nom','like',"%$q%")

                ->orWhere('prenom','like',"%$q%");


            });


        }




        if($request->filled('region')){


            $region=$request->region;


            $query->whereHas('utilisateur',function($user) use($region){


                $user->where('region',$region);


            });


        }




        $query->orderByDesc('score_bayesien')

        ->orderByDesc('avis_count');




        return response()->json(

            $query->get()

        );

    }





    public function show($id)
    {


        $avocat = Avocat::with([

            'utilisateur',

            'profil'

        ])

        ->withCount('avis')

        ->withAvg('avis','note')

        ->find($id);



        if(!$avocat){


            return response()->json([

                'message'=>'Avocat introuvable'

            ],404);


        }



        return response()->json($avocat);

    }


}