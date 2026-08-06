<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DossierApiController;
use App\Http\Controllers\Api\AvocatApiController;
use App\Http\Controllers\Api\RechercheAvocatApiController;
use App\Http\Controllers\Api\DemandeApiController;
use App\Http\Controllers\Api\MessageApiController;
use App\Http\Controllers\Api\AvisApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\RechercheDossierApiController;


Route::post('/register/{role}',[AuthController::class,'register'])
->whereIn('role',['citoyen','avocat']);

Route::post('/login',[AuthController::class,'login']);
Route::post('/contact',[ContactApiController::class,'store']);


Route::middleware('auth:sanctum')->group(function(){

    Route::post('/logout',[AuthController::class,'logout']);

    Route::get('/user',[AuthController::class,'user']);

    Route::put('/profile',[AuthController::class,'updateProfile']);

    Route::put('/profile/password',[AuthController::class,'updatePassword']);

    Route::delete('/profile',[AuthController::class,'deleteAccount']);


    Route::get('/messages/threads',[MessageApiController::class,'threads']);
    Route::get('/messages/{idUtilisateur}',[MessageApiController::class,'show'])
    ->whereNumber('idUtilisateur');

    Route::post('/messages',[MessageApiController::class,'store']);
    Route::post('/messages/fichier',[MessageApiController::class,'storeFile']);

    Route::get('/avocats/{id}/avis',[AvisApiController::class,'index'])
    ->whereNumber('id');


    // CITOYEN
    Route::prefix('citoyen')
    ->middleware('role:citoyen')
    ->group(function(){

        Route::get('/dossiers',[DossierApiController::class,'index']);

        Route::post('/dossiers',[DossierApiController::class,'store']);

        Route::get('/dossiers/{dossier}',[DossierApiController::class,'show']);

        Route::put('/dossiers/{dossier}',[DossierApiController::class,'update']);

        Route::delete('/dossiers/{dossier}',[DossierApiController::class,'destroy']);

        Route::get('/dossiers-stats',[DossierApiController::class,'stats']);


        Route::get('/avocats',[RechercheAvocatApiController::class,'index']);

        Route::get('/avocats/{id}',[RechercheAvocatApiController::class,'show'])
        ->whereNumber('id');


        // citoyen choisit un avocat
        Route::post('/demandes',[DemandeApiController::class,'store']);


        // citoyen répond aux propositions des avocats
        Route::patch('/demandes/{demande}/accepter',
        [DemandeApiController::class,'accepterProposition']);

        Route::patch('/demandes/{demande}/refuser',
        [DemandeApiController::class,'refuserProposition']);


        Route::post('/avis',[AvisApiController::class,'store']);

    });



    // AVOCAT
    Route::prefix('avocat')
    ->middleware('role:avocat')
    ->group(function(){

        Route::get('/stats',[AvocatApiController::class,'stats']);

        Route::get('/profil',[AvocatApiController::class,'profil']);

        Route::put('/profil',[AvocatApiController::class,'updateProfil']);


        Route::get('/demandes',[AvocatApiController::class,'demandes']);


        Route::patch('/demandes/{demande}/accepter',
        [AvocatApiController::class,'accepter']);

        Route::patch('/demandes/{demande}/refuser',
        [AvocatApiController::class,'refuser']);


        Route::patch('/dossiers/{dossier}/resoudre',
        [AvocatApiController::class,'resoudreDossier']);


        Route::get('/dossiers-disponibles',
        [RechercheDossierApiController::class,'index']);


        // avocat propose ses services
        Route::post('/dossiers/{dossier}/proposer',
        [DemandeApiController::class,'proposer']);

    });



    // ADMIN
    Route::prefix('admin')
    ->middleware('role:admin')
    ->group(function(){

        Route::get('/stats',[AdminApiController::class,'stats']);

        Route::get('/avocats-en-attente',[AdminApiController::class,'avocatsEnAttente']);

        Route::get('/utilisateurs',[AdminApiController::class,'utilisateurs']);

        Route::patch('/utilisateurs/{user}/valider',[AdminApiController::class,'validerAvocat']);

        Route::patch('/utilisateurs/{user}/refuser',[AdminApiController::class,'refuserAvocat']);

        Route::patch('/utilisateurs/{user}/suspendre',[AdminApiController::class,'suspendreCompte']);

        Route::patch('/utilisateurs/{user}/reactiver',[AdminApiController::class,'reactiverCompte']);

        Route::delete('/utilisateurs/{user}',[AdminApiController::class,'supprimerCompte']);

        Route::get('/contacts',[ContactApiController::class,'index']);

        Route::patch('/contacts/{contact}/traiter',[ContactApiController::class,'traiter']);

        Route::delete('/contacts/{contact}',[ContactApiController::class,'destroy']);

    });

});