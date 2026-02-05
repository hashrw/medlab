<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Tratamiento
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
                            Tratamiento
                            <span class="text-blue-100 font-medium">
                                {{ $tratamiento->tratamiento ? ' - ' . $tratamiento->tratamiento : '' }}
                            </span>
                        </h3>

                        @php
                            $fechaAsignacion = null;
                            if (!empty($tratamiento->fecha_asignacion)) {
                                try { $fechaAsignacion = \Illuminate\Support\Carbon::parse($tratamiento->fecha_asignacion); }
                                catch (\Exception $e) { $fechaAsignacion = null; }
                            }

                            $duracionTotal = $tratamiento->duracion_total;
                            $paciente = $tratamiento->paciente;
                        @endphp

                        <p class="text-blue-100 mt-1">
                            Fecha asignación:
                            <span class="font-semibold">
                                {{ $fechaAsignacion ? $fechaAsignacion->format('d/m/Y') : '-' }}
                            </span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Duración total:
                            <span class="font-semibold">
                                {{ is_null($duracionTotal) ? '-' : ((int) $duracionTotal . ' días') }}
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
                        @php $backUrl = session('tratamientos_back_url'); @endphp

                        @if($backUrl)
                            <a href="{{ $backUrl }}" class="hover:text-gray-200" title="Volver">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @else
                            <a href="{{ route('tratamientos.index') }}" class="hover:text-gray-200" title="Volver a tratamientos">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @endif

                        @if(Auth::user()->es_medico)
                            <a href="{{ route('tratamientos.edit', $tratamiento->id) }}" class="hover:text-yellow-300" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST" action="{{ route('tratamientos.destroy', $tratamiento->id) }}"
                                  onsubmit="return confirm('¿Eliminar este tratamiento?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="hover:text-red-300" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-8 space-y-10 text-gray-800">

                    {{-- CONTEXTO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Contexto
                        </h4>

                        @php
                            $medico = $tratamiento->medico;
                            $medicoNombre = optional(optional($medico)->user)->name
                                ?? ($medico ? ('Médico #' . $medico->id) : '-');

                            $pacienteId = optional($paciente)->id ?? '-';
                            $pacienteNuhsa = optional($paciente)->nuhsa ?? '-';

                            $dx = $tratamiento->diagnostico ?? null;
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                            <div class="space-y-2">
                                <p>
                                    <strong>Médico:</strong>
                                    {{ $medicoNombre }}
                                </p>

                                <p>
                                    <strong>ID Tratamiento:</strong> {{ $tratamiento->id }}
                                </p>

                                <p>
                                    <strong>Fecha asignación:</strong>
                                    {{ $fechaAsignacion ? $fechaAsignacion->format('d/m/Y') : '-' }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <p>
                                    <strong>ID Paciente:</strong>
                                    {{ $pacienteId }}
                                </p>

                                <p>
                                    <strong>NUHSA:</strong>
                                    {{ $pacienteNuhsa }}
                                </p>

                                <p>
                                    <strong>Diagnóstico origen:</strong>
                                    @if($dx)
                                        <a href="{{ route('diagnosticos.show', $dx->id) }}"
                                           class="text-blue-600 hover:text-blue-800 underline">
                                            Ver diagnóstico
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- DATOS DEL TRATAMIENTO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Tratamiento
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                            <div class="space-y-2">
                                <p><strong>Nombre:</strong> {{ $tratamiento->tratamiento ?? '-' }}</p>
                                <p>
                                    <strong>Duración total:</strong>
                                    {{ is_null($duracionTotal) ? '-' : ((int) $duracionTotal . ' días') }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <p><strong>Descripción:</strong></p>
                                <p class="text-sm text-gray-700">
                                    {{ $tratamiento->descripcion ?: '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- DIAGNÓSTICO ORIGEN (RESUMEN) --}}
                    @if($dx)
                        @php
                            $dxFecha = null;
                            if (!empty($dx->fecha_diagnostico)) {
                                try { $dxFecha = \Illuminate\Support\Carbon::parse($dx->fecha_diagnostico); }
                                catch (\Exception $e) { $dxFecha = null; }
                            }
                        @endphp

                        <div>
                            <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                                Diagnóstico origen
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                                <div class="space-y-2">
                                    <p><strong>Tipo de enfermedad:</strong> {{ $dx->tipo_enfermedad ?? '-' }}</p>
                                    <p><strong>Grado EICH:</strong> {{ $dx->grado_eich ?? '-' }}</p>
                                    <p><strong>Fecha diagnóstico:</strong> {{ $dxFecha ? $dxFecha->format('d/m/Y') : '-' }}</p>
                                </div>

                                <div class="space-y-2">
                                    <p><strong>Estado injerto:</strong> {{ $dx->estado_injerto ?? '-' }}</p>
                                    <p><strong>Regla:</strong> {{ $dx->regla?->nombre ?? '-' }}</p>
                                    <p><strong>Recomendación:</strong> {{ $dx->regla?->tipo_recomendacion ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- LÍNEAS (MEDICAMENTOS) --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Líneas de tratamiento
                        </h4>

                        @php
                            $lineas = $tratamiento->lineasTratamiento ?? collect();
                        @endphp

                        @if($lineas->isEmpty())
                            <p class="text-sm text-gray-600">
                                No hay medicamentos asociados a este tratamiento.
                            </p>
                        @else
                            <div class="overflow-x-auto border rounded">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Medicamento</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Inicio</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fin</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Duración (días)</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Tomas</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($lineas as $med)
                                            @php
                                                $p = $med->pivot;

                                                $ini = '-';
                                                if (!empty($p?->fecha_ini_linea)) {
                                                    try { $ini = \Illuminate\Support\Carbon::parse($p->fecha_ini_linea)->format('d/m/Y'); }
                                                    catch (\Exception $e) { $ini = '-'; }
                                                }

                                                $fin = '-';
                                                if (!empty($p?->fecha_fin_linea)) {
                                                    try { $fin = \Illuminate\Support\Carbon::parse($p->fecha_fin_linea)->format('d/m/Y'); }
                                                    catch (\Exception $e) { $fin = '-'; }
                                                }
                                            @endphp

                                            <tr>
                                                <td class="px-3 py-2">
                                                    {{ $med->nombre ?? '-' }}
                                                    @if(isset($med->miligramos) && $med->miligramos !== null)
                                                        <span class="text-gray-500 text-xs">({{ $med->miligramos }} mg)</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2">{{ $ini }}</td>
                                                <td class="px-3 py-2">{{ $fin }}</td>
                                                <td class="px-3 py-2">{{ $p?->duracion_linea ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $p?->tomas ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $p?->observaciones ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            @if($backUrl)
                                <a href="{{ $backUrl }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                    Volver
                                </a>
                            @else
                                <a href="{{ route('tratamientos.index') }}"
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
