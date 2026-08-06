<x-app-layout>
    <x-slot name="header">Trouver un avocat</x-slot>

    <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
        @php
            $dispoLabels = ['disponible' => 'Disponible', 'en_pause' => 'En pause', 'en_vacances' => 'En vacances'];
        @endphp

        @forelse($avocats as $avocat)
            @php
                $dispo = $avocat->profil->disponibilite ?? 'disponible';
                $dispoColor = $dispo === 'disponible' ? '#8A3B32' : '#B08078';
            @endphp
            <div class="bg-white border rounded-2xl overflow-hidden" style="border-color:#E8DDD9">
                <div class="h-20 flex items-center justify-center" style="background-color:#F4E4E0">
                    <svg class="w-8 h-8" style="color:#8A3B32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="p-3">
                    <p class="text-sm font-semibold" style="color:#3D1414">Me {{ $avocat->utilisateur->nom }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $avocat->profil->specialites ?? 'Spécialité non renseignée' }}</p>
                    <span class="inline-block mt-2 text-xs text-white px-2 py-0.5 rounded-full" style="background-color:{{ $dispoColor }}">{{ $dispoLabels[$dispo] ?? $dispo }}</span>

                    @if($dossiers->isNotEmpty())
                        <form method="POST" action="{{ route('citoyen.demandes.store') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="id_avocat" value="{{ $avocat->id_avocat }}">
                            <select name="id_dossier" class="w-full text-xs border-gray-200 rounded-lg mb-2" required>
                                <option value="">Choisir un dossier</option>
                                @foreach($dossiers as $dossier)
                                    <option value="{{ $dossier->id_dossier }}">{{ $dossier->motif }}</option>
                                @endforeach
                            </select>
                            <button class="w-full text-xs text-white py-1.5 rounded-lg" style="background-color:#5C2020">Envoyer une demande</button>
                        </form>
                    @else
                        <a href="{{ route('citoyen.dossiers.create') }}" class="block mt-3 text-xs text-center py-1.5 rounded-lg border" style="border-color:#5C2020;color:#5C2020">Créer un dossier d'abord</a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 col-span-full">Aucun avocat trouvé.</p>
        @endforelse
    </div>
</x-app-layout>