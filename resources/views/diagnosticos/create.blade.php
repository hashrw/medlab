<x-medico-layout>
    <x-slot name="header">
        <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
            <h3 class="text-lg font-semibold">Crear Nuevo Diagnóstico</h3>
            <a href="{{ route('diagnosticos.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Volver a la Lista
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Información General</h4>

                <x-input-error class="mb-4" :messages="$errors->all()" />

                {{-- Selector de origen del diagnóstico --}}
                <div class="mb-6">
                    <x-input-label for="origen" :value="__('Origen del Diagnóstico')" />
                    <x-select id="origen" name="origen" class="block mt-1 w-full" onchange="toggleSecciones()">
                        <option value="manual" @if(old('origen') == 'manual') selected @endif>Manual</option>
                        <option value="inferido" @if(old('origen') == 'inferido') selected @endif>Inferido por sistema
                        </option>
                    </x-select>
                </div>

                {{-- Formulario Manual --}}
                <form id="form-manual" method="POST" action="{{ route('diagnosticos.store') }}">
                    @csrf
                    @include('diagnosticos.partials.form-fields')
                    <div class="flex items-center justify-end mt-8">
                        <x-danger-button type="button">
                            <a href="{{ route('diagnosticos.index') }}">{{ __('Cancelar') }}</a>
                        </x-danger-button>
                        <x-primary-button class="ml-4">
                            {{ __('Guardar') }}
                        </x-primary-button>
                    </div>
                </form>

                {{-- Botón Inferencia --}}
                <div id="form-inferido" class="hidden">
                    <p class="mb-4 text-sm text-gray-600">
                        El diagnóstico será generado automáticamente a partir de las reglas clínicas definidas.
                    </p>
                    <form method="POST"
                        action="{{ route('diagnosticos.inferir', ['pacienteId' => $pacientes->first()->id ?? 1]) }}">
                        @csrf
                        <x-primary-button>
                            {{ __('Inferir Diagnóstico para este paciente') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSecciones() {
            const origen = document.getElementById('origen').value;
            document.getElementById('form-manual').style.display = origen === 'manual' ? 'block' : 'none';
            document.getElementById('form-inferido').style.display = origen === 'inferido' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', toggleSecciones);
    </script>
</x-medico-layout>