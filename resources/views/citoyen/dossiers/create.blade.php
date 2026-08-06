<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Créer un dossier</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <form method="POST" action="{{ route('citoyen.dossiers.store') }}" class="bg-white p-6 rounded-lg border" style="border-color:#EDEEF0">
            @csrf

            <div>
                <x-input-label for="motif" value="Motif" />
                <x-text-input id="motif" name="motif" class="block mt-1 w-full" :value="old('motif')" required />
                <x-input-error :messages="$errors->get('motif')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="budget" value="Budget (FCFA)" />
                <x-text-input id="budget" name="budget" type="number" step="0.01" class="block mt-1 w-full" :value="old('budget')" />
                <x-input-error :messages="$errors->get('budget')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="niveau_urgence" value="Niveau d'urgence" />
                <select id="niveau_urgence" name="niveau_urgence" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="1">Faible</option>
                    <option value="2" selected>Normale</option>
                    <option value="3">Urgente</option>
                    <option value="4">Critique</option>
                </select>
                <x-input-error :messages="$errors->get('niveau_urgence')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 rounded-md text-white text-sm" style="background-color:#1E3A5F">
                    Créer le dossier
                </button>
            </div>
        </form>
    </div>
</x-app-layout>