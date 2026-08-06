<?php

namespace App\Providers;

use App\Models\Avis;
use App\Observers\AvisObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Avis::observe(AvisObserver::class);
    }
}