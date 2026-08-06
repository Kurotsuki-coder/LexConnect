<x-guest-layout>
    <form method="POST" action="{{ url('register/avocat') }}">
        @csrf

        <div>
            <x-input-label for="nom" :value="__('Nom')" />
            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="telephone" :value="__('Téléphone')" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone')" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="region" :value="__('Région')" />
            <select id="region" name="region" class="block mt-1 w-full border-gray-200 rounded-xl shadow-sm" required>
                <option value="">-- Sélectionner une région --</option>
                <option value="Dakar" @selected(old('region') == 'Dakar')>Dakar</option>
                <option value="Diourbel" @selected(old('region') == 'Diourbel')>Diourbel</option>
                <option value="Fatick" @selected(old('region') == 'Fatick')>Fatick</option>
                <option value="Kaffrine" @selected(old('region') == 'Kaffrine')>Kaffrine</option>
                <option value="Kaolack" @selected(old('region') == 'Kaolack')>Kaolack</option>
                <option value="Kédougou" @selected(old('region') == 'Kédougou')>Kédougou</option>
                <option value="Kolda" @selected(old('region') == 'Kolda')>Kolda</option>
                <option value="Louga" @selected(old('region') == 'Louga')>Louga</option>
                <option value="Matam" @selected(old('region') == 'Matam')>Matam</option>
                <option value="Saint-Louis" @selected(old('region') == 'Saint-Louis')>Saint-Louis</option>
                <option value="Sédhiou" @selected(old('region') == 'Sédhiou')>Sédhiou</option>
                <option value="Tambacounda" @selected(old('region') == 'Tambacounda')>Tambacounda</option>
                <option value="Thiès" @selected(old('region') == 'Thiès')>Thiès</option>
                <option value="Ziguinchor" @selected(old('region') == 'Ziguinchor')>Ziguinchor</option>
            </select>
            <x-input-error :messages="$errors->get('region')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <p class="mt-4 text-sm text-gray-600">{{ __('Ton inscription sera examinée par un administrateur avant activation.') }}</p>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }}
            </a>

            <button type="submit" class="ms-4 px-4 py-2 rounded-md text-white text-sm" style="background-color:#1E3A5F">
                {{ __('S\'inscrire') }}
            </button>
        </div>
    </form>
</x-guest-layout>