<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear médico
            </h2>

            <a href="{{ route('admin.usuarios.create') }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                Volver
            </a>
        </div>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <div class="text-lg font-semibold text-gray-800">Identidad de acceso</div>
                <p class="text-sm text-gray-600 mt-1">
                    Se crea un usuario de acceso (login) y se vincula al perfil médico.
                </p>

                <x-input-error class="mt-4" :messages="$errors->all()" />

                <form method="POST" action="{{ route('admin.usuarios.storeMedico') }}" class="mt-6 space-y-6">
                    @csrf

                    <input type="hidden" name="tipo_usuario_id" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="name" :value="__('Nombre')" />
                            <x-text-input id="name" class="block mt-1 w-full"
                                          type="text" name="name" value="{{ old('name') }}" required />
                        </div>

                        <div>
                            <x-input-label for="apellidos" :value="__('Apellidos')" />
                            <x-text-input id="apellidos" class="block mt-1 w-full"
                                          type="text" name="apellidos" value="{{ old('apellidos') }}" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email (login)')" />
                            <x-text-input id="email" class="block mt-1 w-full"
                                          type="email" name="email" value="{{ old('email') }}" required />
                        </div>

                        <div>
                            <x-input-label for="telefono" :value="__('Teléfono')" />
                            <x-text-input id="telefono" class="block mt-1 w-full"
                                          type="text" name="telefono" value="{{ old('telefono') }}" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Contraseña inicial')" />
                            <x-text-input id="password" class="block mt-1 w-full"
                                          type="password" name="password" required />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                          type="password" name="password_confirmation" required />
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="text-lg font-semibold text-gray-800">Perfil profesional</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="especialidad_id" :value="__('Especialidad')" />
                            <x-select id="especialidad_id" name="especialidad_id" required>
                                <option value="">Selecciona</option>
                                @foreach($especialidades as $e)
                                    <option value="{{ $e->id }}" @selected((string)old('especialidad_id') === (string)$e->id)>
                                        {{ $e->nombre }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <div>
                            <x-input-label for="residente" :value="__('Residente')" />
                            <x-select id="residente" name="residente" required>
                                <option value="">Selecciona</option>
                                <option value="1" @selected(old('residente') === '1')>Sí</option>
                                <option value="0" @selected(old('residente') === '0')>No</option>
                            </x-select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('admin.usuarios.create') }}"
                           class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            Crear médico
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
