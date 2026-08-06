<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CitoyenController extends Controller
{
    public function index()
    {
        $citoyen = Auth::user()->citoyen;
        $dossiers = $citoyen->dossiers()->latest()->get();

        return view('citoyen.dashboard', compact('dossiers'));
    }
}