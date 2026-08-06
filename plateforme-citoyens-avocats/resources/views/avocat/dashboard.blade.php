<x-app-layout>
    <x-slot name="header">
        Mon espace Avocat
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl text-white text-sm" style="background-color:#2F855A">{{ session('success') }}</div>
        @endif

        <div class="bg-white p-6 rounded-2xl border mb-8" style="border-color:#E8DDD9; box-shadow: 0 2px 12px -4px rgba(92,32,32,0.08)">
            <h3 class="font-semibold mb-3" style="color:#5C2020">Mon profil</h3>
            <form method="POST" action="{{ route('avocat.profil.update') }}">
                @csrf @method('PUT')

                <x-input-label for="specialites" value="Spécialités" />
                <x-text-input id="specialites" name="specialites" class="block mt-1 w-full" :value="$avocat->profil->specialites ?? ''" />

                <x-input-label for="bio" value="Bio" class="mt-3" />
                <textarea name="bio" rows="3" class="block mt-1 w-full border-gray-200 rounded-xl shadow-sm">{{ $avocat->profil->bio ?? '' }}</textarea>

                <x-input-label for="disponibilite" value="Disponibilité" class="mt-3" />
                <select name="disponibilite" class="block mt-1 w-full border-gray-200 rounded-xl shadow-sm">
                    <option value="disponible" @selected(($avocat->profil->disponibilite ?? '') == 'disponible')>Disponible</option>
                    <option value="en_pause" @selected(($avocat->profil->disponibilite ?? '') == 'en_pause')>En pause</option>
                    <option value="en_vacances" @selected(($avocat->profil->disponibilite ?? '') == 'en_vacances')>En vacances</option>
                </select>

                <x-input-label for="horaire" value="Horaire" class="mt-3" />
                <x-text-input id="horaire" name="horaire" class="block mt-1 w-full" :value="$avocat->profil->horaire ?? ''" />

                <x-input-label for="numero_barre" value="Numéro de barreau" class="mt-3" />
                <x-text-input id="numero_barre" name="numero_barre" class="block mt-1 w-full" :value="$avocat->profil->numero_barre ?? ''" />

                <button type="submit" class="mt-4 px-5 py-2.5 rounded-xl text-white text-xs font-semibold uppercase tracking-widest"
                        style="background-color:#5C2020">
                    Mettre à jour
                </button>
            </form>
        </div>

        <h3 class="font-semibold mb-3" style="color:#5C2020">Demandes reçues</h3>
        @if($demandes->isEmpty())
            <p class="text-gray-500">Aucune demande pour l'instant.</p>
        @endif

        @foreach($demandes as $demande)
            <div class="p-4 mb-3 rounded-2xl border bg-white" style="border-color:#E8DDD9; box-shadow: 0 2px 12px -4px rgba(92,32,32,0.08)">
                <p class="text-sm text-gray-700">{{ $demande->message }}</p>
                <p class="text-xs mt-1" style="color:#8A6A63">Statut : {{ $demande->statut_demande }}</p>

                @if($demande->statut_demande === 'envoyee')
                    <div class="mt-2 flex gap-2">
                        <form method="POST" action="{{ route('avocat.demandes.accepter', $demande) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 rounded-xl text-white text-xs" style="background-color:#2F855A">Accepter</button>
                        </form>
                        <form method="POST" action="{{ route('avocat.demandes.refuser', $demande) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 rounded-xl text-white text-xs" style="background-color:#C0392B">Refuser</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>