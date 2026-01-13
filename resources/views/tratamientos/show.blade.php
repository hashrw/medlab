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

                        <p class="text-blue-100 mt-1">
                            Fecha asignación:
                            <span class="font-semibold">
                                {{ $tratamiento->fecha_asignacion?->format('d/m/Y') ?? '-' }}
                            </span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Duración total:
                            <span class="font-semibold">
                                {{ $tratamiento->duracion_total ?? 0 }} días
                            </span>
                        </p>

                        @if($tratamiento->paciente)
                            <p class="text-blue-100 mt-1">
                                Paciente:
                                <span class="font-semibold">
                                    {{ $tratamiento->paciente->nuhsa ?? ('Paciente #' . $tratamiento->paciente->id) }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <div class="flex space-x-4 text-lg">
                        <a href="{{ route('tratamientos.index') }}" class="hover:text-gray-200" title="Volver a tratamientos">
                            <i class="fas fa-arrow-left"></i>
                        </a>

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
                    </div>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-8 space-y-10 text-gray-800">

                    {{-- CONTEXTO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Contexto
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                            <div class="space-y-2">
                                <p>
                                    <strong>Médico:</strong>
                                    @if($tratamiento->medico && $tratamiento->medico->user)
                                        {{ $tratamiento->medico->user->name ?? ('Médico #' . $tratamiento->medico->id) }}
                                    @elseif($tratamiento->medico)
                                        {{ 'Médico #' . $tratamiento->medico->id }}
                                    @else
                                        -
                                    @endif
                                </p>

                                <p>
                                    <strong>ID Tratamiento:</strong> {{ $tratamiento->id }}
                                </p>

                                <p>
                                    <strong>Fecha asignación:</strong> {{ $tratamiento->fecha_asignacion?->format('d/m/Y') ?? '-' }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <p>
                                    <strong>ID Paciente:</strong>
                                    {{ $tratamiento->paciente?->id ?? '-' }}
                                </p>

                                <p>
                                    <strong>NUHSA:</strong>
                                    {{ $tratamiento->paciente?->nuhsa ?? '-' }}
                                </p>

                                <p>
                                    <strong>Diagnóstico origen:</strong>
                                    @if($tratamiento->diagnostico)
                                        <a href="{{ route('diagnosticos.show', $tratamiento->diagnostico->id) }}"
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
                                <p><strong>Duración total:</strong> {{ $tratamiento->duracion_total ?? 0 }} días</p>
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
                    @if($tratamiento->diagnostico)
                        @php
                            $dx = $tratamiento->diagnostico;
                        @endphp

                        <div>
                            <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                                Diagnóstico origen
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                                <div class="space-y-2">
                                    <p><strong>Tipo de enfermedad:</strong> {{ $dx->tipo_enfermedad ?? '-' }}</p>
                                    <p><strong>Grado EICH:</strong> {{ $dx->grado_eich ?? '-' }}</p>
                                    <p><strong>Fecha diagnóstico:</strong> {{ $dx->fecha_diagnostico?->format('d/m/Y') ?? '-' }}</p>
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
                                                $ini = $p->fecha_ini_linea ? \Illuminate\Support\Carbon::parse($p->fecha_ini_linea)->format('d/m/Y') : '-';
                                                $fin = $p->fecha_fin_linea ? \Illuminate\Support\Carbon::parse($p->fecha_fin_linea)->format('d/m/Y') : '-';
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
                                                <td class="px-3 py-2">{{ $p->duracion_linea ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $p->tomas ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $p->observaciones ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('tratamientos.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                Volver
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>
