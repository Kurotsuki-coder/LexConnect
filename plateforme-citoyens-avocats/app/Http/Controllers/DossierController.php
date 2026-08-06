<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DossierController extends Controller
{
    public function index()
    {
        $dossiers = Auth::user()->citoyen->dossiers()->latest()->get();
        return view('citoyen.dossiers.index', compact('dossiers'));
    }

    public function create()
    {
        return view('citoyen.dossiers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'motif' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'niveau_urgence' => 'required|integer|between:1,4',
        ]);

        Auth::user()->citoyen->dossiers()->create([
            'motif' => $request->motif,
            'description' => $request->description,
            'budget' => $request->budget,
            'niveau_urgence' => $request->niveau_urgence,
            'statut_dossier' => 'ouvert',
        ]);

        return redirect()->route('citoyen.dossiers.index')->with('success', 'Dossier créé.');
    }

    public function show(Dossier $dossier)
    {
        $this->authorizeAccess($dossier);
        return view('citoyen.dossiers.show', compact('dossier'));
    }

    public function edit(Dossier $dossier)
    {
        $this->authorizeAccess($dossier);
        return view('citoyen.dossiers.edit', compact('dossier'));
    }

    public function update(Request $request, Dossier $dossier)
    {
        $this->authorizeAccess($dossier);

        $request->validate([
            'motif' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'niveau_urgence' => 'required|integer|between:1,4',
        ]);

        $dossier->update($request->only('motif', 'description', 'budget', 'niveau_urgence'));

        return redirect()->route('citoyen.dossiers.show', $dossier)->with('success', 'Dossier modifié.');
    }

    public function destroy(Dossier $dossier)
    {
        $this->authorizeAccess($dossier);
        $dossier->delete();
        return redirect()->route('citoyen.dossiers.index');
    }

    public function cloturer(Dossier $dossier)
    {
        $this->authorizeAccess($dossier);
        $dossier->update(['statut_dossier' => 'cloture']);
        return back()->with('success', 'Dossier clôturé.');
    }

    private function authorizeAccess(Dossier $dossier): void
    {
        if ($dossier->id_citoyen !== Auth::user()->citoyen->id_citoyen) {
            abort(403);
        }
    }
}