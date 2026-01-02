<x-medico-layout>
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

            {{-- Bloque: Contexto paciente (si existe) --}}
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Paciente</h4>

                @if($paciente)
                    <x-show-field label="ID Paciente" :value="$paciente->id" />
                    <x-show-field label="NUHSA" :value="$paciente->nuhsa" />

                    @if($paciente->usuarioAcceso)
                        <x-show-field label="Nombre" :value="$paciente->usuarioAcceso->name" />
                        <x-show-field label="Email" :value="$paciente->usuarioAcceso->email" />
                    @endif

                    <x-show-field label="Días desde trasplante" :value="$diasDesdeTrasplante ?? '-'" />
                    <x-show-field
                        label="Fecha último trasplante"
                        :value="$ultimoTrasplante?->fecha_trasplante ? $ultimoTrasplante->fecha_trasplante->format('d/m/Y') : '-'"
                    />
                @else
                    <p class="text-sm text-gray-600">Este diagnóstico no tiene paciente asociado.</p>
                @endif
            </div>

            {{-- Bloque: Diagnóstico --}}
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Diagnóstico</h4>

                <x-show-field label="Fecha diagnóstico" :value="$diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-'" />
                <x-show-field label="Tipo de enfermedad" :value="$diagnostico->tipo_enfermedad ?? '-'" />
                <x-show-field label="Grado EICH" :value="$diagnostico->grado_eich ?? '-'" />
                <x-show-field label="Escala Karnofsky" :value="$diagnostico->escala_karnofsky ?? '-'" />
                <x-show-field label="Estado del injerto" :value="$diagnostico->estado_injerto ?? '-'" />

                <x-show-field label="Estado" :value="$diagnostico->estado?->estado ?? '-'" />
                <x-show-field label="Comienzo" :value="$diagnostico->comienzo?->tipo_comienzo ?? '-'" />
                <x-show-field label="Infección" :value="$diagnostico->infeccion?->nombre ?? '-'" />
                <x-show-field label="Origen" :value="$diagnostico->origen?->origen ?? '-'" />

                <x-show-field label="Observaciones">
                    <p class="text-sm text-gray-700">{{ $diagnostico->observaciones ?: '-' }}</p>
                </x-show-field>

                @if($diagnostico->regla)
                    <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded">
                        <h5 class="text-md font-semibold text-gray-700 mb-2">Regla aplicada</h5>

                        <p class="text-sm text-gray-800">
                            <strong>Nombre:</strong> {{ $diagnostico->regla->nombre ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-800">
                            <strong>Prioridad:</strong> {{ $diagnostico->regla->prioridad ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-800">
                            <strong>Recomendación clínica:</strong> {{ $diagnostico->regla->tipo_recomendacion ?? '-' }}
                        </p>

                        @if(!empty($diagnostico->regla->descripcion_clinica))
                            <p class="text-sm text-gray-800 mt-2">
                                <strong>Descripción clínica:</strong> {{ $diagnostico->regla->descripcion_clinica }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Bloque: Síntomas asociados --}}
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Síntomas Asociados</h4>

                @if($diagnostico->sintomas->isEmpty())
                    <p class="text-sm text-gray-600">No hay síntomas asociados a este diagnóstico.</p>
                @else
                    <table class="w-full table-auto text-sm">
                        <thead class="bg-gray-100 text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">Síntoma</th>
                                <th class="px-4 py-2 text-left">Fecha diagnóstico</th>
                                <th class="px-4 py-2 text-left">Score NIH</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($diagnostico->sintomas as $sintoma)
                                @php
                                    $fd = $sintoma->pivot->fecha_diagnostico ?? null;
                                    $fdFormatted = $fd ? \Illuminate\Support\Carbon::parse($fd)->format('d/m/Y') : '-';
                                @endphp
                                <tr>
                                    <td class="px-4 py-2">{{ $sintoma->sintoma }}</td>
                                    <td class="px-4 py-2">{{ $fdFormatted }}</td>
                                    <td class="px-4 py-2">{{ $sintoma->pivot->score_nih ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('diagnosticos.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                        Volver
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-medico-layout>
