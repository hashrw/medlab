<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alta de paciente
            </h2>

            {{-- Antes no había "Volver" (inconsistente con Alta médico) --}}
            <a href="{{ route('admin.usuarios.create') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-1 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow">

            <form method="POST" action="{{ route('admin.usuarios.storePaciente') }}" enctype="multipart/form-data">
                @csrf

                {{-- DATOS DE ACCESO --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Datos de acceso
                </h3>

                <div class="grid grid-cols-12 gap-4 mb-6 items-start">

                    {{-- AVATAR --}}
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

                                    {{-- BASURA CORREGIDA:
                                         Antes: @error('avatar') -> NO existe en rules.
                                         Correcto: @error('foto') porque el input se llama name="foto"
                                    --}}
                                    @error('foto')
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
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Apellidos (acordado) --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                                <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('apellidos')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-span-12 md:col-span-8">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Teléfono (tu Request lo valida y tu controller lo guarda; faltaba en vista) --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('telefono')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Passwords --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <input type="password" name="password" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('password_confirmation')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <hr class="my-6">

                {{-- DATOS CLÍNICOS DEL PACIENTE --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Datos clínicos del paciente
                </h3>

                <div class="grid grid-cols-12 gap-4 mb-6">
                    {{-- NUHSA --}}
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">NUHSA</label>

                        <div class="mt-1 flex">
                            <span
                                class="inline-flex items-center px-3 border border-r-0 border-gray-300 rounded-l-md bg-gray-100 text-gray-600 text-sm">
                                AN
                            </span>

                            <input type="text" name="nuhsa" inputmode="numeric" pattern="[0-9]*" maxlength="10" required
                                placeholder="10 dígitos"
                                value="{{ old('nuhsa') ? preg_replace('/^AN/i', '', old('nuhsa')) : '' }}"
                                class="w-full border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            El sistema añadirá el prefijo automáticamente.
                        </p>

                        @error('nuhsa')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha nacimiento --}}
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('fecha_nacimiento')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sexo --}}
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Sexo</label>
                        <select name="sexo" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Seleccionar</option>
                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                        </select>
                        @error('sexo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Peso (acordado) --}}
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                        <input type="number" name="peso" step="0.1" min="1" max="500" value="{{ old('peso') }}"
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('peso')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Altura (acordado) --}}
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Altura (cm)</label>
                        <input type="number" name="altura" step="1" min="30" max="300" value="{{ old('altura') }}"
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('altura')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Asignar médico --}}
                    <div class="col-span-12 md:col-span-8">
                        <label class="block text-sm font-medium text-gray-700">Asignar Médico</label>
                        <select name="medico_id" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Seleccionar</option>
                            @foreach ($medicos as $medico)
                                <option value="{{ $medico->id }}" @selected((string) old('medico_id') === (string) $medico->id)>
                                    {{ $medico->user?->name }} {{ $medico->user?->apellidos }}
                                </option>
                            @endforeach
                        </select>
                        @error('medico_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.usuarios.create') }}" class="px-4 py-2 border rounded-md text-gray-700">
                        Cancelar
                    </a>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                        Crear paciente
                    </button>
                </div>
            </form>
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
