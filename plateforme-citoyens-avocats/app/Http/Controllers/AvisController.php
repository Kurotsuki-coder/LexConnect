<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_avocat' => 'required|exists:avocats,id_avocat',
            'note' => 'required|integer|between:1,5',
            'commentaire' => 'nullable|string',
        ]);

        Avis::create([
            'id_citoyen' => Auth::user()->citoyen->id_citoyen,
            'id_avocat' => $request->id_avocat,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
        ]);

        return back()->with('success', 'Avis publié.');
    }
}