<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Espace Admin</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-3 rounded-md text-white text-sm" style="background-color:#2F855A">{{ session('success') }}</div>
        @endif

        <h3 class="font-semibold mb-3" style="color:#1E3A5F">Avocats en attente de validation</h3>
        @if($avocatsEnAttente->isEmpty())
            <p class="text-gray-500 mb-6">Aucune inscription en attente.</p>
        @endif

        @foreach($avocatsEnAttente as $user)
            <div class="p-4 mb-3 rounded-lg border bg-white flex justify-between items-center" style="border-color:#EDEEF0">
                <div>
                    <p class="font-medium">{{ $user->nom }} {{ $user->prenom }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.avocats.valider', $user) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 rounded-md text-white text-xs" style="background-color:#2F855A">Valider</button>
                    </form>
                    <form method="POST" action="{{ route('admin.avocats.refuser', $user) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 rounded-md text-white text-xs" style="background-color:#C0392B">Refuser</button>
                    </form>
                </div>
            </div>
        @endforeach

        <h3 class="font-semibold mb-3 mt-8" style="color:#1E3A5F">Tous les utilisateurs</h3>
        @foreach($utilisateurs as $user)
            <div class="p-4 mb-3 rounded-lg border bg-white flex justify-between items-center" style="border-color:#EDEEF0">
                <div>
                    <p class="font-medium">{{ $user->nom }} {{ $user->prenom }} <span class="text-xs text-gray-400">({{ $user->role }})</span></p>
                    <p class="text-xs text-gray-400">{{ $user->email }} — statut : {{ $user->statut }}</p>
                </div>
                <div class="flex gap-2">
                    @if($user->statut !== 'suspendu')
                        <form method="POST" action="{{ route('admin.utilisateurs.suspendre', $user) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 rounded-md text-white text-xs" style="background-color:#D69E2E">Suspendre</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.utilisateurs.reactiver', $user) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 rounded-md text-white text-xs" style="background-color:#2F855A">Réactiver</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.utilisateurs.supprimer', $user) }}" onsubmit="return confirm('Supprimer ce compte ?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 rounded-md text-white text-xs" style="background-color:#C0392B">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>