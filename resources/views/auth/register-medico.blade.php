<x-guest-layout>
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
            <div class="mb-6">
                <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Acceso clínico</div>
                <h1 class="text-xl font-semibold text-gray-800">Alta de médico</h1>
                <p class="text-sm text-gray-600 mt-1">
                </p>
            </div>

            <form method="POST" action="{{ route('register-medico') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="tipo_usuario_id" value="1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input id="name" class="block mt-1 w-full"
                                      type="text" name="name" :value="old('name')" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="apellidos" :value="__('Apellidos')" />
                        <x-text-input id="apellidos" class="block mt-1 w-full"
                                      type="text" name="apellidos" :value="old('apellidos')" required />
                        <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full"
                                  type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="telefono" :value="__('Teléfono (opcional)')" />
                    <x-text-input id="telefono" class="block mt-1 w-full"
                                  type="text" name="telefono" :value="old('telefono')" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input id="password" class="block mt-1 w-full"
                                      type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                      type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div class="text-sm font-semibold text-gray-800 mb-2">Perfil asistencial</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="especialidad_id" :value="__('Especialidad')" />
                            <x-select id="especialidad_id" name="especialidad_id" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                @foreach(($especialidads ?? []) as $esp)
                                    <option value="{{ $esp->id }}" @selected(old('especialidad_id') == $esp->id)>
                                        {{ $esp->nombre }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('especialidad_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="residente" :value="__('Residente')" />
                            <x-select id="residente" name="residente" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                <option value="1" @selected(old('residente')==='1')>Sí</option>
                                <option value="0" @selected(old('residente')==='0')>No</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('residente')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        ¿Ya tienes acceso?
                    </a>

                    <x-primary-button>
                        Crear médico
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
