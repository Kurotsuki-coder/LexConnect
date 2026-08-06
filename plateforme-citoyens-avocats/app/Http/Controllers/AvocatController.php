<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AvocatController extends Controller
{
    public function index()
    {
        $avocat = Auth::user()->avocat;
        $demandes = $avocat->demandes()->latest('heure')->get();

        return view('avocat.dashboard', compact('avocat', 'demandes'));
    }
}