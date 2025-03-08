<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="hidden" value="1" name="tipo_usuario_id">

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="fecha_contratacion" :value="__('Fecha contratación')" />

            <x-text-input id="fecha_contratacion" class="block mt-1 w-full"
                     type="date"
                     name="fecha_contratacion"
                     :value="old('fecha_contratacion')"
                     required />
            <x-input-error :messages="$errors->get('fecha_contratacion')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="vacunado" :value="__('Vacunado')" />

            <x-select id="vacunado" name="vacunado" required>
                <option value="">{{__('Elige una opción')}}</option>
                <option value="1" @if (old('vacunado') == 1) selected @endif>{{__('Sí')}}</option>
                <option value="0" @if (old('vacunado') == 0) selected @endif>{{__('No')}}</option>
            </x-select>
            <x-input-error :messages="$errors->get('vacunado')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="sueldo" :value="__('Sueldo')" />
            <x-text-input id="sueldo" class="block mt-1 w-full" min="0" step="1" type="number" name="sueldo" :value="old('sueldo')" required />
            <x-input-error :messages="$errors->get('sueldo')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="especialidad_id" :value="__('Especialidad')" />
            <x-select id="especialidad_id" name="especialidad_id" required>
                <option value="">{{__('Elige una opción')}}</option>
                @foreach ($especialidads as $especialidad)
                    <option value="{{$especialidad->id}}" @if (old('especialidad_id') == $especialidad->id) selected @endif>{{$especialidad->nombre}}</option>
                @endforeach
            </x-select>
            <x-input-error :messages="$errors->get('especialidad_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
