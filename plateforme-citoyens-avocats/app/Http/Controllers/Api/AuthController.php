<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Citoyen;
use App\Models\Avocat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request, string $role)
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telephone' => 'nullable|string|max:20',
            'region' => 'required|in:Dakar,Diourbel,Fatick,Kaffrine,Kaolack,Kédougou,Kolda,Louga,Matam,Saint-Louis,Sédhiou,Tambacounda,Thiès,Ziguinchor',
        ];

        if ($role === 'avocat') {
            $rules['numero_barre'] = 'required|string|max:50';
        }

        $request->validate($rules);

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
            $avocat = Avocat::create(['id_utilisateur' => $utilisateur->id_utilisateur]);
            $avocat->profil()->create(['numero_barre' => $request->numero_barre]);
        }

        Auth::login($utilisateur);
        $request->session()->regenerate();

        if ($role === 'avocat') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['user' => $utilisateur, 'pending' => true]);
        }

        return response()->json(['user' => $utilisateur->load(['citoyen', 'avocat'])]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        $user = Auth::user();

        if (in_array($user->statut, ['suspendu', 'supprime', 'refuse', 'en_attente'])) {
            Auth::logout();

            $labels = [
                'suspendu' => 'Ce compte a été suspendu',
                'supprime' => 'Ce compte a été supprimé',
                'refuse' => 'Cette inscription a été refusée',
                'en_attente' => 'Votre inscription est en attente de validation par un administrateur',
            ];

            $message = $labels[$user->statut];
            if ($user->motif_statut) {
                $message .= ' pour le motif suivant : ' . $user->motif_statut;
            }

            return response()->json(['message' => $message], 403);
        }

        $request->session()->regenerate();

        return response()->json(['user' => $user->load(['citoyen', 'avocat', 'admin'])]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Déconnecté.']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load(['citoyen', 'avocat', 'admin']));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'region' => 'required|in:Dakar,Diourbel,Fatick,Kaffrine,Kaolack,Kédougou,Kolda,Louga,Matam,Saint-Louis,Sédhiou,Tambacounda,Thiès,Ziguinchor',
        ]);

        $user = Auth::user();
        $user->update($request->only('nom', 'prenom', 'telephone', 'region'));

        return response()->json($user->load(['citoyen', 'avocat', 'admin']));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->mot_de_passe)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        $user->update(['mot_de_passe' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Mot de passe mis à jour.']);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        Auth::guard('web')->logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Compte supprimé.']);
    }
}