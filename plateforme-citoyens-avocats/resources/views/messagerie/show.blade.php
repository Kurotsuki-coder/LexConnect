<x-app-layout>
    <x-slot name="header">
        {{ auth()->user()->role === 'citoyen' ? 'Me ' : '' }}{{ $correspondant->prenom }} {{ $correspondant->nom }}
    </x-slot>

    <div class="bg-white rounded-2xl border p-4 mb-4" style="border-color:#EDEEF0; min-height:320px">
        @forelse($messages as $message)
            @php $mine = $message->id_expediteur === auth()->user()->id_utilisateur; @endphp
            <div class="mb-3 flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[70%] px-3 py-2 rounded-2xl text-sm"
                     style="background-color:{{ $mine ? '#E6F1FB' : '#F8F7F4' }}; color:#1F2328">
                    {{ $message->contenu }}
                    <div class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($message->heure)->format('H:i') }}</div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-8">Débutez la conversation.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route(auth()->user()->role.'.messages.store') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="id_receveur" value="{{ $correspondant->id_utilisateur }}">
        <input type="text" name="contenu" class="flex-1 border-gray-200 rounded-xl shadow-sm" placeholder="Écris ton message..." required>
        <button type="submit" class="px-5 py-2 rounded-xl text-white text-sm font-medium"
                style="background-color:{{ auth()->user()->role === 'citoyen' ? '#1E3A5F' : '#5C2020' }}">
            Envoyer
        </button>
    </form>
</x-app-layout>