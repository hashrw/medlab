<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva prueba clínica
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                <div class="px-6 py-4 bg-blue-800 text-white">
                    <h3 class="text-lg font-semibold">Registro de prueba clínica</h3>

                    @if(isset($paciente))
                        <p class="text-xs text-blue-100 mt-1">
                            Paciente: {{ $paciente->nombre }} (NUHSA: {{ $paciente->nuhsa }})
                        </p>
                    @endif
                </div>

                <div class="p-8 space-y-6 text-gray-800">
                    <x-input-error class="mb-4" :messages="$errors->all()" />

                    @php
                        $isNested = isset($paciente);
                        $action = $isNested
                            ? route('pacientes.pruebas.store', $paciente)
                            : route('pruebas.store');
                        $cancel = $isNested
                            ? route('pacientes.show', $paciente)
                            : route('pruebas.index');
                    @endphp

                    <form method="POST" action="{{ $action }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <x-input-label for="nombre" value="Nombre de la prueba" />
                                <x-text-input
                                    id="nombre"
                                    name="nombre"
                                    class="block mt-1 w-full"
                                    :value="old('nombre')"
                                    required
                                />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tipo_prueba_id" value="Tipo de prueba" />
                                <select name="tipo_prueba_id" id="tipo_prueba_id"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccione...</option>
                                    @foreach(($tipos ?? []) as $tipo)
                                        <option value="{{ $tipo->id }}" @selected(old('tipo_prueba_id') == $tipo->id)>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_prueba_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fecha" value="Fecha de la prueba" />
                                <x-text-input
                                    type="date"
                                    id="fecha"
                                    name="fecha"
                                    class="block mt-1 w-full"
                                    :value="old('fecha')"
                                />
                                <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="resultado" value="Resultado" />
                                <textarea name="resultado" id="resultado" rows="3"
                                          class="w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ old('resultado') }}</textarea>
                                <x-input-error :messages="$errors->get('resultado')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="comentario" value="Comentario clínico" />
                                <textarea name="comentario" id="comentario" rows="2"
                                          class="w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ old('comentario') }}</textarea>
                                <x-input-error :messages="$errors->get('comentario')" class="mt-2" />
                            </div>

                            {{-- No-nested: selección de paciente --}}
                            @if(!$isNested)
                                <div class="md:col-span-2">
                                    <x-input-label for="paciente_id" value="Paciente" />
                                    <select name="paciente_id" id="paciente_id"
                                            class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Seleccione paciente...</option>
                                        @foreach(($pacientes ?? []) as $p)
                                            <option value="{{ $p->id }}" @selected(old('paciente_id') == $p->id)>
                                                {{ $p->nombre }} ({{ $p->nuhsa }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('paciente_id')" class="mt-2" />
                                </div>
                            @endif

                        </div>

                        <div class="flex justify-end gap-3 pt-8 border-t">
                            <a href="{{ $cancel }}"
                               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                                Guardar prueba
                            </button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>