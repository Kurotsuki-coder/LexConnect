<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LexConnect') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="background-color:#FFFFFF">
@php
    $role = auth()->user()->role ?? null;
    $navDark = match($role) { 'avocat' => '#5C2020', 'admin' => '#444441', default => '#1E3A5F' };
    $navLight = match($role) { 'avocat' => '#A0524B', 'admin' => '#6B6B66', default => '#3B5A7A' };
    $navTint = match($role) { 'avocat' => '#F4E4E0', 'admin' => '#EDEDEC', default => '#E6F1FB' };
@endphp
<div class="min-h-screen flex">
    <aside class="w-56 flex-shrink-0 py-5" style="background-color:{{ $navDark }}">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-5 pb-5 mb-4" style="border-bottom:1px solid rgba(255,255,255,0.15)">
            <x-application-logo class="h-7 w-auto fill-current text-white" />
            <span class="text-white font-semibold text-sm">LexConnect</span>
        </a>
        <nav class="flex flex-col gap-1 px-3">
            @if($role === 'citoyen')
                <a href="{{ route('citoyen.index') }}" class="px-3 py-2 rounded-xl text-sm {{ request()->routeIs('citoyen.index') ? 'text-white' : 'text-white/70 hover:text-white' }}" @if(request()->routeIs('citoyen.index')) style="background-color:{{ $navLight }}" @endif>Tableau de bord</a>
                <a href="{{ route('citoyen.dossiers.index') }}" class="px-3 py-2 rounded-xl text-sm {{ request()->routeIs('citoyen.dossiers.*') ? 'text-white' : 'text-white/70 hover:text-white' }}" @if(request()->routeIs('citoyen.dossiers.*')) style="background-color:{{ $navLight }}" @endif>Mes dossiers</a>
                <a href="{{ route('citoyen.avocats.index') }}" class="px-3 py-2 rounded-xl text-sm {{ request()->routeIs('citoyen.avocats.*') ? 'text-white' : 'text-white/70 hover:text-white' }}" @if(request()->routeIs('citoyen.avocats.*')) style="background-color:{{ $navLight }}" @endif>Trouver un avocat</a>
                <a href="{{ route('citoyen.messages.index') }}" class="px-3 py-2 rounded-xl text-sm text-white/70 hover:text-white">Messagerie</a>
            @elseif($role === 'avocat')
                <a href="{{ route('avocat.index') }}" class="px-3 py-2 rounded-xl text-sm {{ request()->routeIs('avocat.index') ? 'text-white' : 'text-white/70 hover:text-white' }}" @if(request()->routeIs('avocat.index')) style="background-color:{{ $navLight }}" @endif>Tableau de bord</a>
                <a href="{{ route('avocat.messages.index') }}" class="px-3 py-2 rounded-xl text-sm text-white/70 hover:text-white">Messagerie</a>
            @elseif($role === 'admin')
                <a href="{{ route('admin.index') }}" class="px-3 py-2 rounded-xl text-sm text-white">Tableau de bord</a>
            @endif
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <div class="h-16 flex items-center justify-between px-6" style="border-bottom:1px solid #EDEEF0">
            <form method="GET" action="{{ $role === 'citoyen' ? route('citoyen.avocats.index') : '#' }}" class="flex items-center gap-2 rounded-xl px-3 h-9 w-80" style="background-color:{{ $navTint }}">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un avocat par nom" class="bg-transparent border-0 focus:ring-0 text-sm flex-1 p-0">
            </form>

            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-semibold text-white" style="background-color:{{ $navDark }}">
                        {{ strtoupper(substr(auth()->user()->prenom ?? '?',0,1).substr(auth()->user()->nom ?? '?',0,1)) }}
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">{{ __('Mon profil') }}</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Se déconnecter') }}</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        @isset($header)
            <div class="px-6 pt-5">
                <span class="text-lg font-semibold" style="color:{{ $navDark }}">{{ $header }}</span>
            </div>
        @endisset

        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>