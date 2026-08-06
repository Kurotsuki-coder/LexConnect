<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Citoyen;
use App\Models\Avocat;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function createCitoyen(): View
    {
        return view('auth.register-citoyen');
    }

    public function createAvocat(): View
    {
        return view('auth.register-avocat');
    }

    public function store(Request $request, string $role): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telephone' => 'nullable|string|max:20',
            'region' => 'required|in:Dakar,Diourbel,Fatick,Kaffrine,Kaolack,Kédougou,Kolda,Louga,Matam,Saint-Louis,Sédhiou,Tambacounda,Thiès,Ziguinchor',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'role' => $role,
            'statut' => $role === 'avocat' ? 'en_attente' : 'actif',
            'region' => $request->region,
        ]);

        if ($role === 'citoyen') {
            Citoyen::create(['id_utilisateur' => $utilisateur->id_utilisateur]);
        } elseif ($role === 'avocat') {
            Avocat::create(['id_utilisateur' => $utilisateur->id_utilisateur]);
        }

        event(new Registered($utilisateur));
        Auth::login($utilisateur);

        return redirect('/dashboard');
    }
}