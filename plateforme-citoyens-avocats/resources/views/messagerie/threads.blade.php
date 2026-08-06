<x-app-layout>
    <x-slot name="header">Messagerie</x-slot>

    @php $role = auth()->user()->role; @endphp

    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#EDEEF0">
        @forelse($threads as $thread)
            @php $c = $thread['correspondant']; $m = $thread['dernier_message']; @endphp
            <a href="{{ route($role.'.messages.show', $c->id_utilisateur) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition" style="border-bottom:1px solid #F1F1EF">
                <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-semibold text-white flex-shrink-0"
                     style="background-color:{{ $role === 'citoyen' ? '#5C2020' : '#1E3A5F' }}">
                    {{ strtoupper(substr($c->prenom,0,1).substr($c->nom,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ $role === 'citoyen' ? 'Me ' : '' }}{{ $c->prenom }} {{ $c->nom }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $m->contenu ?? 'Aucun message pour l’instant — dis bonjour !' }}</p>
                </div>
                @if($m)
                    <span class="text-xs text-gray-400 flex-shrink-0">{{ \Carbon\Carbon::parse($m->heure)->format('d/m') }}</span>
                @endif
            </a>
        @empty
            <p class="text-sm text-gray-500 p-6 text-center">
                Aucune discussion pour l'instant. Une messagerie s'ouvre automatiquement dès qu'une demande est acceptée.
            </p>
        @endforelse
    </div>
</x-app-layout>