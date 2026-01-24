<x-guest-layout>
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
            <div class="mb-6">
                <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Acceso clínico</div>
                <h1 class="text-xl font-semibold text-gray-800">Alta de paciente</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Crear usuario de acceso y ficha clínica mínima del paciente.
                </p>
            </div>

            <form method="POST" action="{{ route('register-paciente') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="tipo_usuario_id" value="2">

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
                    <div class="text-sm font-semibold text-gray-800 mb-2">Datos clínicos mínimos</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nuhsa" :value="__('NUHSA')" />
                            <x-text-input id="nuhsa" class="block mt-1 w-full"
                                          type="text" name="nuhsa" :value="old('nuhsa')" required />
                            <x-input-error :messages="$errors->get('nuhsa')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="fecha_nacimiento" :value="__('Fecha de nacimiento')" />
                            <x-text-input id="fecha_nacimiento" class="block mt-1 w-full"
                                          type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sexo" :value="__('Sexo')" />
                            <x-select id="sexo" name="sexo" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                <option value="M" @selected(old('sexo')==='M')>Masculino</option>
                                <option value="F" @selected(old('sexo')==='F')>Femenino</option>
                                <option value="O" @selected(old('sexo')==='O')>Otro</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="medico_id" :value="__('Médico asignado (opcional)')" />
                            <x-select id="medico_id" name="medico_id">
                                <option value="">{{ __('Sin asignar') }}</option>
                                @foreach(($medicos ?? []) as $m)
                                    <option value="{{ $m->id }}" @selected(old('medico_id') == $m->id)>
                                        {{ $m->user->name ?? ('Médico #' . $m->id) }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('medico_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        ¿Ya tienes acceso?
                    </a>

                    <x-primary-button>
                        Crear paciente
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
