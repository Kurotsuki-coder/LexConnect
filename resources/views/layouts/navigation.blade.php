@php
    $navColor = match(auth()->user()->role ?? null) {
        'avocat' => '#5C2020',
        'admin' => '#444441',
        default => '#1E3A5F',
    };
    $navColorLight = match(auth()->user()->role ?? null) {
        'avocat' => '#A0524B',
        'admin' => '#6B6B66',
        default => '#3B5A7A',
    };
@endphp
<nav x-data="{ open: false }" style="background-color:{{ $navColor }}" class="shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto fill-current text-white" />
                        <span class="text-white font-semibold hidden sm:block">LexConnect</span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition"
                                style="background-color:{{ $navColorLight }}; color:white;">
                            {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mon profil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Se déconnecter') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background-color:{{ $navColorLight }}">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('profile.edit') }}" class="block text-white/90 py-2">{{ __('Mon profil') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block text-white/90 py-2">
                    {{ __('Se déconnecter') }}
                </a>
            </form>
        </div>
    </div>
</nav>