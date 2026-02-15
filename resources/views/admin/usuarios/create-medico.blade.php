<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alta médico
            </h2>

            <a href="{{ route('admin.usuarios.create') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Volver
            </a>
        </div>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    <div class="py-1">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border rounded-lg p-6 shadow-sm">

                <div class="text-lg font-semibold text-gray-800">Identidad de acceso</div>
                <p class="text-sm text-gray-600 mt-1">
                    Se crea un usuario de acceso y se vincula al perfil médico.
                </p>

                <form method="POST" action="{{ route('admin.usuarios.storeMedico') }}" enctype="multipart/form-data"
                    class="mt-6 space-y-6">
                    @csrf

                    <input type="hidden" name="tipo_usuario_id" value="1">

                    {{-- BLOQUE: AVATAR (izq) + CAMPOS (der) --}}
                    <div class="grid grid-cols-12 gap-4 items-start">

                        {{-- AVATAR (izquierda) --}}
                        <div class="col-span-12 md:col-span-3 md:pt-6">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 h-full">
                                <div class="flex flex-col items-center text-center gap-3 h-full justify-center">
                                    <button type="button" id="avatarPickerBtn"
                                        class="w-28 h-28 rounded-full border border-gray-300 bg-white overflow-hidden flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        title="Seleccionar foto">
                                        <img id="avatarPreview" src="" alt="Preview avatar"
                                            class="hidden w-full h-full object-cover" />
                                        <span id="avatarPlaceholder" class="text-sm text-gray-500 px-2">
                                            Sin foto
                                        </span>
                                    </button>

                                    <div class="w-full">
                                        <button type="button" id="avatarChooseBtn"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-white">
                                            Seleccionar archivo
                                        </button>

                                        <button type="button" id="avatarClearBtn"
                                            class="w-full mt-2 px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-white hidden">
                                            Quitar
                                        </button>

                                        <p class="mt-2 text-xs text-gray-500">
                                            JPG/PNG/WebP. Máx. 2MB.
                                        </p>

                                        @error('avatar')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <input id="avatarInput" type="file" name="foto" accept="image/*" class="hidden" />
                                </div>
                            </div>
                        </div>

                        {{-- CAMPOS (derecha del avatar) --}}
                        <div class="col-span-12 md:col-span-9">
                            <div class="grid grid-cols-12 gap-4">

                                {{-- Nombre --}}
                                <div class="col-span-12 md:col-span-6">
                                    <x-input-label for="name" :value="__('Nombre')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        value="{{ old('name') }}" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                {{-- Apellidos --}}
                                <div class="col-span-12 md:col-span-6">
                                    <x-input-label for="apellidos" :value="__('Apellidos')" />
                                    <x-text-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos"
                                        value="{{ old('apellidos') }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('apellidos')" />
                                </div>

                                {{-- Email (ancho) --}}
                                <div class="col-span-12 md:col-span-8">
                                    <x-input-label for="email" :value="__('Email (login)')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        value="{{ old('email') }}" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                {{-- Teléfono (medio) --}}
                                <div class="col-span-12 md:col-span-4">
                                    <x-input-label for="telefono" :value="__('Teléfono')" />
                                    <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono"
                                        value="{{ old('telefono') }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
                                </div>

                                {{-- Password + confirmation juntos (abajo) --}}
                                <div class="col-span-12 md:col-span-6">
                                    <x-input-label for="password" :value="__('Contraseña inicial')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                                </div>

                            </div>
                        </div>

                    </div>

                    <hr class="my-6">

                    <div class="text-lg font-semibold text-gray-800">Perfil profesional</div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-6">
                            <x-input-label for="especialidad_id" :value="__('Especialidad')" />
                            <x-select id="especialidad_id" name="especialidad_id" class="mt-1 w-full" required>
                                <option value="">Selecciona</option>
                                @foreach ($especialidades as $e)
                                    <option value="{{ $e->id }}" @selected((string) old('especialidad_id') === (string) $e->id)>
                                        {{ $e->nombre }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('especialidad_id')" />
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <x-input-label for="residente" :value="__('Residente')" />
                            <x-select id="residente" name="residente" class="mt-1 w-full" required>
                                <option value="">Selecciona</option>
                                <option value="1" @selected(old('residente') === '1')>Sí</option>
                                <option value="0" @selected(old('residente') === '0')>No</option>
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('residente')" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('admin.usuarios.create') }}"
                            class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            Crear médico
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const input = document.getElementById('avatarInput');
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                const pickBtn = document.getElementById('avatarPickerBtn');
                const chooseBtn = document.getElementById('avatarChooseBtn');
                const clearBtn = document.getElementById('avatarClearBtn');

                if (!input || !preview || !placeholder || !pickBtn || !chooseBtn || !clearBtn) return;

                function openPicker() { input.click(); }

                function setPreview(file) {
                    const url = URL.createObjectURL(file);
                    preview.src = url;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    clearBtn.classList.remove('hidden');
                    preview.onload = () => URL.revokeObjectURL(url);
                }

                function clearPreview() {
                    input.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    clearBtn.classList.add('hidden');
                }

                pickBtn.addEventListener('click', openPicker);
                chooseBtn.addEventListener('click', openPicker);
                clearBtn.addEventListener('click', clearPreview);

                input.addEventListener('change', function () {
                    const file = input.files && input.files[0] ? input.files[0] : null;
                    if (!file) return clearPreview();
                    if (!file.type || !file.type.startsWith('image/')) return clearPreview();
                    setPreview(file);
                });
            })();
        </script>
    @endpush
</x-app-layout>