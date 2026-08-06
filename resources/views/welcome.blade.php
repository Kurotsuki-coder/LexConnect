<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LexConnect') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="background-color:#F8F7F4">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 text-center">

        <div class="flex items-center gap-2 mb-1">
            <x-application-logo class="w-9 h-9 fill-current" style="color:#1E3A5F" />
            <span class="text-2xl font-bold" style="color:#1E3A5F">LexConnect</span>
        </div>
        <p class="text-sm text-gray-500 mb-10">La justice, à portée de clic — Sénégal</p>

        <div class="flex flex-col sm:flex-row gap-5">
            <a href="{{ route('register.citoyen') }}"
               class="w-64 border rounded-2xl p-6 transition hover:shadow-lg"
               style="border-color:#DCE3EA; background-color:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background-color:#E6F1FB">
                    <svg class="w-6 h-6" style="color:#1E3A5F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <p class="font-semibold text-sm mb-1" style="color:#1E3A5F">Je suis Citoyen</p>
                <p class="text-xs text-gray-500 mb-4">Trouver un avocat pour mon besoin</p>
                <div class="text-white text-xs font-semibold uppercase tracking-widest rounded-xl py-2.5" style="background-color:#1E3A5F">
                    Commencer
                </div>
            </a>

            <a href="{{ route('register.avocat') }}"
               class="w-64 border rounded-2xl p-6 transition hover:shadow-lg"
               style="border-color:#E8DDD9; background-color:#FBF3F1">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background-color:#F4E4E0">
                    <svg class="w-6 h-6" style="color:#5C2020" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <p class="font-semibold text-sm mb-1" style="color:#5C2020">Je suis Avocat</p>
                <p class="text-xs text-gray-500 mb-4">Recevoir des demandes de clients</p>
                <div class="text-white text-xs font-semibold uppercase tracking-widest rounded-xl py-2.5" style="background-color:#5C2020">
                    Commencer
                </div>
            </a>
        </div>

        <p class="text-xs text-gray-400 mt-8">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="underline" style="color:#1E3A5F">Se connecter</a>
        </p>
    </div>
</body>
</html>