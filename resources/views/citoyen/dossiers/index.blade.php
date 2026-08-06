<x-app-layout>
    <x-slot name="header">Mes dossiers</x-slot>

    <a href="{{ route('citoyen.dossiers.create') }}" class="inline-block mb-5 px-4 py-2 rounded-xl text-white text-sm font-medium" style="background-color:#C9A15A">
        + Nouveau dossier
    </a>

    <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
        @php
            $urgenceColors = [1 => '#2F855A', 2 => '#3B5A7A', 3 => '#D69E2E', 4 => '#C0392B'];
            $urgenceLabels = [1 => 'Faible', 2 => 'Normale', 3 => 'Urgente', 4 => 'Critique'];
        @endphp

        @forelse($dossiers as $dossier)
            <a href="{{ route('citoyen.dossiers.show', $dossier) }}" class="bg-white border rounded-2xl overflow-hidden hover:shadow-md transition" style="border-color:#EDEEF0">
                <div class="h-16 flex items-center justify-center" style="background-color:#E6F1FB">
                    <svg class="w-7 h-7" style="color:#1E3A5F" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="p-3">
                    <p class="text-sm font-semibold" style="color:#1E3A5F">{{ $dossier->motif }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($dossier->description, 60) }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs text-white px-2 py-0.5 rounded-full" style="background-color:{{ $urgenceColors[$dossier->niveau_urgence] }}">
                            {{ $urgenceLabels[$dossier->niveau_urgence] }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $dossier->statut_dossier }}</span>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-sm text-gray-500 col-span-full">Aucun dossier pour l'instant.</p>
        @endforelse
    </div>
</x-app-layout>