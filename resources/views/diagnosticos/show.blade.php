<x-app-layout>
    <x-slot name="header">
        <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
            <h3 class="text-lg font-semibold">Ver Diagnóstico</h3>
            <a href="{{ route('diagnosticos.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Volver a la Lista
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Información General -->
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Información General</h4>

                <x-show-field label="Días desde Trasplante" :value="$diagnostico->dias_desde_trasplante" />
                <x-show-field label="Tipo de Enfermedad" :value="$diagnostico->tipo_enfermedad" />
                <x-show-field label="Estado de Enfermedad" :value="$diagnostico->estado_enfermedad" />
                <x-show-field label="Comienzo Crónica" :value="$diagnostico->comienzo_cronica" />
                <x-show-field label="Estado del Injerto" :value="$diagnostico->estado_injerto" />
                <x-show-field label="Tipo de Infección" :value="$diagnostico->tipo_infeccion" />
            </div>

            <!-- Fechas Clínicas -->
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Fechas Clínicas</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-show-field label="Fecha de Trasplante" :value="$diagnostico->f_trasplante?->format('d/m/Y H:i')" />
                    <x-show-field label="Electromiografía" :value="$diagnostico->f_electromiografia?->format('d/m/Y H:i')" />
                    <x-show-field label="Evaluación Injerto" :value="$diagnostico->f_eval_injerto?->format('d/m/Y H:i')" />
                    <x-show-field label="Medulograma" :value="$diagnostico->f_medulograma?->format('d/m/Y H:i')" />
                    <x-show-field label="Espirometría" :value="$diagnostico->f_espirometria?->format('d/m/Y H:i')" />
                    <x-show-field label="Esplenectomía" :value="$diagnostico->f_esplenectomia?->format('d/m/Y H:i')" />
                </div>
            </div>

            <!-- Evaluaciones y Observaciones -->
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Evaluaciones y Observaciones</h4>

                <x-show-field label="Hipoalbuminemia" :value="$diagnostico->hipoalbuminemia ? 'Sí' : 'No'" />
                <x-show-field label="Observaciones">
                    <p class="text-sm text-gray-700">{{ $diagnostico->observaciones }}</p>
                </x-show-field>
            </div>

            <!-- Síntomas -->
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Síntomas Asociados</h4>

                <table class="w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Síntoma</th>
                            <th class="px-4 py-2 text-left">Fecha Diagnóstico</th>
                            <th class="px-4 py-2 text-left">Score NIH</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($diagnostico->sintomas as $sintoma)
                            <tr>
                                <td class="px-4 py-2">{{ $sintoma->nombre }}</td>
                                <td class="px-4 py-2">{{ $sintoma->pivot->fecha_diagnostico->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $sintoma->pivot->score_nih }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex items-center justify-end mt-6">
                    <x-danger-button type="button">
                        <a href="{{ route('diagnosticos.index') }}">
                            {{ __('Volver') }}
                        </a>
                    </x-danger-button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
