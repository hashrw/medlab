<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Diagnóstico
        </h2>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    <div class="py-1">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold">
                            Diagnóstico
                            <span class="text-blue-100 font-medium">
                                {{ $diagnostico->tipo_enfermedad ? ' - ' . $diagnostico->tipo_enfermedad : '' }}
                            </span>
                        </h3>

                        <p class="text-blue-100 mt-1">
                            Fecha:
                            <span class="font-semibold">
                                {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}
                            </span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Grado EICH:
                            <span class="font-semibold">
                                {{ $diagnostico->grado_eich ?? '-' }}
                            </span>
                        </p>

                        @if($paciente)
                        <p class="text-blue-100 mt-1">
                            Paciente:
                            <span class="font-semibold">
                            {{ $paciente->nuhsa ?? ('Paciente #' . $paciente->id) }}
                        </span>
                    </p>
                    @endif

                    </div>

                    <div class="flex space-x-4 text-lg">
                        @if($paciente)
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="hover:text-gray-200" title="Volver al paciente">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @else
                            <a href="{{ route('diagnosticos.index') }}" class="hover:text-gray-200" title="Volver a diagnósticos">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @endif

                        <a href="{{ route('diagnosticos.edit', $diagnostico->id) }}" class="hover:text-yellow-300" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('diagnosticos.destroy', $diagnostico->id) }}"
                              onsubmit="return confirm('¿Eliminar este diagnóstico?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="hover:text-red-300" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-8 space-y-10 text-gray-800">

                    {{-- SECCIÓN: CONTEXTO DEL PACIENTE --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Contexto del paciente
                        </h4>

                        @if($paciente)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                                <div class="space-y-2">
                                    <p><strong>ID Paciente:</strong> {{ $paciente->id }}</p>
                                    <p><strong>NUHSA:</strong> {{ $paciente->nuhsa ?? '-' }}</p>

                                    {{--  @if($paciente->usuarioAcceso)
                                        <p><strong>Nombre:</strong> {{ $paciente->usuarioAcceso->name ?? '-' }}</p>
                                        <p><strong>Email:</strong> {{ $paciente->usuarioAcceso->email ?? '-' }}</p>
                                    @endif --}}
                                </div>

                                <div class="space-y-2">
                                    <p><strong>Días desde trasplante:</strong> {{ $diasDesdeTrasplante ?? '-' }}</p>
                                    <p>
                                        <strong>Fecha último trasplante:</strong>
                                        {{ $ultimoTrasplante?->fecha_trasplante ? $ultimoTrasplante->fecha_trasplante->format('d/m/Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-600">Este diagnóstico no tiene paciente asociado.</p>
                        @endif
                    </div>

                    {{-- SECCIÓN: DIAGNÓSTICO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Diagnóstico
                        </h4>

                        @php
                            $tipo = strtolower(trim($diagnostico->tipo_enfermedad ?? ''));
                            $esCronica = in_array($tipo, ['eich crónica', 'eich cronica'], true);
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                            <div class="space-y-2">
                                <p><strong>Fecha diagnóstico:</strong> {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}</p>
                                <p><strong>Tipo de enfermedad:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                                <p><strong>Grado EICH:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                                <p><strong>Estado del injerto:</strong> {{ $diagnostico->estado_injerto ?? '-' }}</p>
                            </div>

                            <div class="space-y-2">
                                <p><strong>Estado:</strong> {{ $diagnostico->estado?->estado ?? '-' }}</p>
                                <p><strong>Infección:</strong> {{ $diagnostico->infeccion?->nombre ?? '-' }}</p>

                                @if($esCronica)
                                    <p><strong>Escala Karnofsky:</strong> {{ $diagnostico->escala_karnofsky ?? '-' }}</p>
                                    <p><strong>Comienzo:</strong> {{ $diagnostico->comienzo?->tipo_comienzo ?? '-' }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm md:text-base">
                                <strong>Observaciones:</strong>
                            </p>
                            <p class="text-sm text-gray-700 mt-1">
                                {{ $diagnostico->observaciones ?: '-' }}
                            </p>
                        </div>

                        {{-- REGLA APLICADA --}}
                        @if($diagnostico->regla)
                            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded">
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

                    {{-- SECCIÓN: SÍNTOMAS ASOCIADOS --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Síntomas asociados
                        </h4>

                        @if($diagnostico->sintomas->isEmpty())
                            <p class="text-sm text-gray-600">No hay síntomas asociados a este diagnóstico.</p>
                        @else
                            <div class="overflow-x-auto border rounded">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Síntoma</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha diagnóstico</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Score NIH</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($diagnostico->sintomas as $sintoma)
                                            @php
                                                $fd = $sintoma->pivot->fecha_diagnostico ?? null;
                                                $fdFormatted = $fd ? \Illuminate\Support\Carbon::parse($fd)->format('d/m/Y') : '-';
                                            @endphp
                                            <tr>
                                                <td class="px-3 py-2">{{ $sintoma->sintoma ?? $sintoma->nombre ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $fdFormatted }}</td>
                                                <td class="px-3 py-2">{{ $sintoma->pivot->score_nih ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            @if($paciente)
                                <a href="{{ route('pacientes.show', $paciente->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                    Volver a paciente
                                </a>
                            @else
                                <a href="{{ route('diagnosticos.index') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                    Volver
                                </a>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>
