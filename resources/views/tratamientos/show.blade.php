<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Tratamiento
        </h2>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    @php
        $fechaAsignacion = null;
        if (!empty($tratamiento->fecha_asignacion)) {
            try {
                $fechaAsignacion = \Illuminate\Support\Carbon::parse($tratamiento->fecha_asignacion);
            } catch (\Exception $e) {
                $fechaAsignacion = null;
            }
        }

        $duracionTotal = $tratamiento->duracion_total;
        $paciente = $tratamiento->paciente;
        $medico = $tratamiento->medico;
        $dx = $tratamiento->diagnostico ?? null;
        $lineas = $tratamiento->lineasTratamiento ?? collect();
        $backUrl = session('tratamientos_back_url');

        $esInferido = !is_null($tratamiento->diagnostico_id);

        $medicoNombre = optional(optional($medico)->user)->name
            ?? ($medico ? ('Médico #' . $medico->id) : '-');

        $pacienteNombre = $tratamiento->paciente?->usuarioAcceso?->name ?? 'Paciente';
        $pacienteNuhsa = optional($paciente)->nuhsa ?? '-';

        $dxFecha = null;
        if ($dx && !empty($dx->fecha_diagnostico)) {
            try {
                $dxFecha = \Illuminate\Support\Carbon::parse($dx->fecha_diagnostico);
            } catch (\Exception $e) {
                $dxFecha = null;
            }
        }
    @endphp

    <div class="py-1">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $tratamiento->tratamiento ?? 'Tratamiento' }}
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @if($esInferido)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-sm font-semibold">
                                    Tratamiento inferido
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-sm font-semibold">
                                    Tratamiento manual
                                </span>
                            @endif

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-white/15 text-white text-sm font-semibold">
                                {{ $lineas->count() }} línea(s) de medicación
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-100">
                            <p>
                                <span class="font-semibold text-white">Paciente:</span>
                                {{ $pacienteNombre }}
                            </p>

                            <p>
                                <span class="font-semibold text-white">Asignación:</span>
                                {{ $fechaAsignacion ? $fechaAsignacion->format('d/m/Y') : '-' }}
                            </p>

                            <p>
                                <span class="font-semibold text-white">Duración:</span>
                                {{ is_null($duracionTotal) ? '-' : ((int) $duracionTotal . ' días') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex space-x-4 text-lg">
                        <a href="{{ $backUrl ?: route('tratamientos.index') }}" class="hover:text-gray-200"
                            title="Volver">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        @if(Auth::user()->es_medico)
                            <a href="{{ route('tratamientos.edit', $tratamiento->id) }}" class="hover:text-yellow-300"
                                title="Editar">
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

                {{-- CONTENIDO COMPACTO --}}
                <div class="p-6 space-y-6 text-gray-800">

                    {{-- RESUMEN PRINCIPAL --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-3">
                                Contexto
                            </h4>

                            <div class="space-y-2 text-sm">
                                <p><strong>ID Tratamiento:</strong> {{ $tratamiento->id }}</p>
                                {{-- <p><strong>Médico:</strong> {{ $medicoNombre }}</p> --}}
                                <p><strong>Paciente:</strong> {{ $pacienteNuhsa }}</p>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-3">
                                Tratamiento
                            </h4>

                            <div class="space-y-2 text-sm">
                                <p><strong>Nombre:</strong> {{ $tratamiento->tratamiento ?? '-' }}</p>
                                <p><strong>Estado:</strong> {{ $tratamiento->estado_tratamiento ?? '-' }}</p>
                                <p><strong>Duración total:</strong>
                                    {{ is_null($duracionTotal) ? '-' : ((int) $duracionTotal . ' días') }}</p>
                            </div>
                        </div>

                        <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wide mb-3">
                                Origen clínico
                            </h4>

                            @if($dx)
                                <div class="space-y-2 text-sm">
                                    <p><strong>Tipo:</strong> {{ $dx->tipo_enfermedad ?? '-' }}</p>
                                    <p><strong>Grado EICH:</strong> {{ $dx->grado_eich ?? '-' }}</p>
                                    <p><strong>Estado injerto:</strong> {{ $dx->estado_injerto ?? '-' }}</p>

                                    <a href="{{ route('diagnosticos.show', $dx->id) }}"
                                        class="inline-flex items-center mt-2 px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded text-sm">
                                        Ver diagnóstico origen
                                    </a>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">
                                    Tratamiento sin diagnóstico asociado.
                                </p>
                            @endif
                        </div>

                    </div>

                    {{-- LÍNEAS DE TRATAMIENTO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Líneas de tratamiento
                        </h4>

                        @if($lineas->isEmpty())
                            <p class="text-sm text-gray-600">
                                No hay medicamentos asociados a este tratamiento.
                            </p>
                        @else
                            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Medicamento</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Inicio</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Fin</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Duración</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Tomas</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Observaciones</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($lineas as $med)
                                            @php
                                                $p = $med->pivot;

                                                $ini = '-';
                                                if (!empty($p?->fecha_ini_linea)) {
                                                    try {
                                                        $ini = \Illuminate\Support\Carbon::parse($p->fecha_ini_linea)->format('d/m/Y');
                                                    } catch (\Exception $e) {
                                                        $ini = '-';
                                                    }
                                                }

                                                $fin = '-';
                                                if (!empty($p?->fecha_fin_linea)) {
                                                    try {
                                                        $fin = \Illuminate\Support\Carbon::parse($p->fecha_fin_linea)->format('d/m/Y');
                                                    } catch (\Exception $e) {
                                                        $fin = '-';
                                                    }
                                                }
                                            @endphp

                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 font-medium">
                                                    {{ $med->nombre ?? '-' }}
                                                    @if(isset($med->miligramos) && $med->miligramos !== null)
                                                        <span class="text-gray-500 text-xs">({{ $med->miligramos }} mg)</span>
                                                    @endif
                                                </td>

                                                <td class="px-4 py-3">{{ $ini }}</td>
                                                <td class="px-4 py-3">{{ $fin }}</td>

                                                <td class="px-4 py-3">
                                                    {{ is_null($p?->duracion_linea) ? '-' : ((int) $p->duracion_linea . ' días') }}
                                                </td>

                                                <td class="px-4 py-3">{{ $p?->tomas ?? '-' }}</td>
                                                <td class="px-4 py-3">{{ $p?->observaciones ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    @if($tratamiento->descripcion)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-2">
                                Descripción
                            </h4>

                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $tratamiento->descripcion }}
                            </p>
                        </div>
                    @endif
                    {{-- JUSTIFICACIÓN --}}
                    @if($dx)
                        <details class="border border-blue-200 rounded-lg overflow-hidden group">
                            <summary
                                class="px-4 py-3 bg-gray-50 hover:bg-blue-50 border-b border-blue-100 text-gray-800 flex justify-between items-center cursor-pointer list-none transition">
                                <div>
                                    <h4 class="font-semibold text-blue-700">
                                        Justificación del tratamiento
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        Regla clínica y diagnóstico que originaron la propuesta terapéutica.
                                    </p>
                                </div>

                                <span class="text-sm text-blue-700 font-semibold group-open:hidden">
                                    Ver detalle
                                </span>

                                <span class="text-sm text-blue-700 font-semibold hidden group-open:inline">
                                    Ocultar
                                </span>
                            </summary>

                            <div class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm bg-white">
                                <div>
                                    <p class="text-gray-500">Fecha diagnóstico</p>
                                    <p class="font-semibold">{{ $dxFecha ? $dxFecha->format('d/m/Y') : '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Regla aplicada</p>
                                    <p class="font-semibold">{{ $dx->regla?->nombre ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Recomendación</p>
                                    <p class="font-semibold">{{ $dx->regla?->tipo_recomendacion ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Resultado</p>
                                    <p class="font-semibold">{{ $dx->grado_eich ?? '-' }}</p>
                                </div>
                            </div>
                        </details>
                    @endif

                    <div class="flex items-center justify-end pt-2">
                        <a href="{{ $backUrl ?: route('tratamientos.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            Volver
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>