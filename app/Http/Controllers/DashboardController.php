<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return match (Auth::user()->role) {
            'citoyen' => redirect('/dashboard/citoyen'),
            'avocat' => redirect('/dashboard/avocat'),
            'admin' => redirect('/dashboard/admin'),
            default => abort(403),
        };
    }
}