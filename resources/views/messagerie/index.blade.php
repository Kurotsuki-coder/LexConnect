<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Messagerie</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white p-6 rounded-lg border mb-4" style="border-color:#EDEEF0">
            @forelse($messages as $message)
                <div class="mb-3 p-3 rounded-md {{ $message->id_expediteur === auth()->user()->id_utilisateur ? 'ml-auto text-right' : '' }}"
                     style="background-color:{{ $message->id_expediteur === auth()->user()->id_utilisateur ? '#EDEEF0' : '#F8F7F4' }}; max-width:80%;">
                    <p class="text-sm">{{ $message->contenu }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $message->heure }}</p>
                </div>
            @empty
                <p class="text-gray-500">Aucun message.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route(auth()->user()->role . '.messages.store') }}">
            @csrf
            <input type="hidden" name="id_receveur" value="{{ $avecId }}">
            <div class="flex gap-2">
                <input type="text" name="contenu" class="flex-1 border-gray-300 rounded-md shadow-sm" placeholder="Écris ton message..." required>
                <button type="submit" class="px-4 py-2 rounded-md text-white text-sm" style="background-color:#1E3A5F">Envoyer</button>
            </div>
        </form>
    </div>
</x-app-layout>