<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alta de paciente
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">

            <form method="POST" action="{{ route('admin.usuarios.storePaciente') }}">
                @csrf

                {{-- DATOS DE ACCESO --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Datos de acceso
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- DATOS CLÍNICOS DEL PACIENTE --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Datos clínicos del paciente
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- NUHSA --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NUHSA</label>

                        {{--
                            Prefijo visual AN:
                            - El admin introduce SOLO los 10 dígitos
                            - prepareForValidation() añadirá AN automáticamente
                        --}}
                        <div class="mt-1 flex">
                            <span
                                class="inline-flex items-center px-3 border border-r-0 border-gray-300 rounded-l-md bg-gray-100 text-gray-600 text-sm">
                                AN
                            </span>

                            <input
                                type="text"
                                name="nuhsa"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="10"
                                required
                                placeholder="10 dígitos"
                                value="{{ old('nuhsa') ? preg_replace('/^AN/i', '', old('nuhsa')) : '' }}"
                                class="w-full border-gray-300 rounded-r-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            El sistema añadirá el prefijo automáticamente.
                        </p>

                        @error('nuhsa')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FECHA NACIMIENTO --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input
                            type="date"
                            name="fecha_nacimiento"
                            value="{{ old('fecha_nacimiento') }}"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                        @error('fecha_nacimiento')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SEXO --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sexo</label>
                        <select
                            name="sexo"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="">Seleccionar</option>
                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                        </select>
                        @error('sexo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ASIGNAR MÉDICO --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Asignar Médico</label>
                        <select
                            name="medico_id"
                            required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="">Seleccionar</option>

                            {{--
                                Se espera que el controller pase:
                                $medicos = Medico::with('user')->orderBy('id')->get()
                                Mostramos "Nombre Apellidos" como etiqueta.
                            --}}
                            @foreach ($medicos as $medico)
                                <option
                                    value="{{ $medico->id }}"
                                    @selected((string) old('medico_id') === (string) $medico->id)
                                >
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
                    <a
                        href="{{ route('dashboard.admin') }}"
                        class="px-4 py-2 border rounded-md text-gray-700"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md"
                    >
                        Crear paciente
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
