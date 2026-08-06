<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $contact = Contact::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'sujet' => $request->sujet,
            'message' => $request->message,
            'statut' => 'nouveau',
            'id_utilisateur' => Auth::check() ? Auth::id() : null,
        ]);

        return response()->json($contact, 201);
    }

    public function index()
    {
        return response()->json(Contact::latest()->get());
    }

    public function traiter(Contact $contact)
    {
        $contact->update(['statut' => 'traite']);
        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['message' => 'Message supprimé.']);
    }
}