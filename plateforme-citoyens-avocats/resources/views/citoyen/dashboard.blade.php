<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mon espace Citoyen</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold" style="color:#1E3A5F">Mes dossiers</h3>
            <a href="{{ route('citoyen.dossiers.create') }}" class="px-4 py-2 rounded-md text-white text-sm" style="background-color:#C9A15A">
                + Nouveau dossier
            </a>
        </div>

        @if($dossiers->isEmpty())
            <p class="text-gray-500">Aucun dossier pour l'instant.</p>
        @else
            <div class="grid gap-4">
                @foreach($dossiers as $dossier)
                    @php
                        $urgenceColors = [1 => '#2F855A', 2 => '#3B5A7A', 3 => '#D69E2E', 4 => '#C0392B'];
                        $urgenceLabels = [1 => 'Faible', 2 => 'Normale', 3 => 'Urgente', 4 => 'Critique'];
                    @endphp
                    <a href="{{ route('citoyen.dossiers.show', $dossier) }}" class="block p-4 rounded-lg border bg-white hover:shadow-md transition" style="border-color:#EDEEF0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold" style="color:#1E3A5F">{{ $dossier->motif }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($dossier->description, 100) }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color:{{ $urgenceColors[$dossier->niveau_urgence] }}">
                                {{ $urgenceLabels[$dossier->niveau_urgence] }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-400">Statut : {{ $dossier->statut_dossier }}</div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>