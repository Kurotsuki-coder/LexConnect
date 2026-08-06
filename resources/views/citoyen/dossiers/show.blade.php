<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $dossier->motif }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-3 rounded-md text-white text-sm" style="background-color:#2F855A">{{ session('success') }}</div>
        @endif

        <div class="bg-white p-6 rounded-lg border" style="border-color:#EDEEF0">
            <p class="text-gray-700">{{ $dossier->description }}</p>
            <p class="mt-2 text-sm text-gray-500">Budget : {{ $dossier->budget ? number_format($dossier->budget, 0, ',', ' ') . ' FCFA' : 'Non précisé' }}</p>
            <p class="mt-1 text-sm text-gray-500">Statut : {{ $dossier->statut_dossier }}</p>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('citoyen.dossiers.edit', $dossier) }}" class="px-3 py-2 rounded-md text-white text-sm" style="background-color:#3B5A7A">Modifier</a>

                @if($dossier->statut_dossier !== 'cloture')
                    <form method="POST" action="{{ route('citoyen.dossiers.cloturer', $dossier) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-2 rounded-md text-white text-sm" style="background-color:#C0392B">Clôturer</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="mt-6 bg-white p-6 rounded-lg border" style="border-color:#EDEEF0">
            <h3 class="font-semibold mb-3" style="color:#1E3A5F">Envoyer une demande à un avocat</h3>
            <form method="POST" action="{{ route('citoyen.demandes.store') }}">
                @csrf
                <input type="hidden" name="id_dossier" value="{{ $dossier->id_dossier }}">
                <x-input-label for="id_avocat" value="ID de l'avocat" />
                <x-text-input id="id_avocat" name="id_avocat" type="number" class="block mt-1 w-full" required />
                <x-input-label for="message" value="Message" class="mt-3" />
                <textarea name="message" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
                <button type="submit" class="mt-3 px-4 py-2 rounded-md text-white text-sm" style="background-color:#1E3A5F">Envoyer la demande</button>
            </form>
        </div>
    </div>
</x-app-layout>