<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CitoyenController;
use App\Http\Controllers\AvocatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfilAvocatController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\RechercheAvocatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:citoyen'])->prefix('dashboard/citoyen')->name('citoyen.')->group(function () {
    Route::get('/', [CitoyenController::class, 'index'])->name('index');
    Route::resource('dossiers', DossierController::class);
    Route::patch('dossiers/{dossier}/cloturer', [DossierController::class, 'cloturer'])->name('dossiers.cloturer');
    Route::post('demandes', [DemandeController::class, 'store'])->name('demandes.store');
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{idUtilisateur}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('avis', [AvisController::class, 'store'])->name('avis.store');
    Route::get('avocats', [RechercheAvocatController::class, 'index'])->name('avocats.index');
});

Route::middleware(['auth', 'role:avocat'])->prefix('dashboard/avocat')->name('avocat.')->group(function () {
    Route::get('/', [AvocatController::class, 'index'])->name('index');
    Route::put('profil', [ProfilAvocatController::class, 'update'])->name('profil.update');
    Route::patch('demandes/{demande}/accepter', [DemandeController::class, 'accepter'])->name('demandes.accepter');
    Route::patch('demandes/{demande}/refuser', [DemandeController::class, 'refuser'])->name('demandes.refuser');
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{idUtilisateur}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('dashboard/admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::patch('utilisateurs/{user}/valider', [AdminController::class, 'validerAvocat'])->name('avocats.valider');
    Route::patch('utilisateurs/{user}/refuser', [AdminController::class, 'refuserAvocat'])->name('avocats.refuser');
    Route::patch('utilisateurs/{user}/suspendre', [AdminController::class, 'suspendreCompte'])->name('utilisateurs.suspendre');
    Route::patch('utilisateurs/{user}/reactiver', [AdminController::class, 'reactiverCompte'])->name('utilisateurs.reactiver');
    Route::delete('utilisateurs/{user}', [AdminController::class, 'supprimerCompte'])->name('utilisateurs.supprimer');
});

require __DIR__.'/auth.php';