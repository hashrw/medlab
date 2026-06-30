<x-medico-layout>
    <div class="py-3 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-6 bg-blue-800 text-white flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h3 class="text-xl font-semibold">
                            Gestión de tratamientos
                        </h3>

                        <p class="text-sm text-blue-100 mt-1">
                            Seguimiento terapéutico asociado a los pacientes del médico.
                        </p>
                    </div>

                    @if(Auth::user()->es_medico)
                        <a href="{{ route('tratamientos.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                            .
                        </a>
                    @endif
                </div>

                {{-- CONTENIDO --}}
                <div class="p-6 bg-gray-50">
                    @if($tratamientos->isEmpty())
                        <div class="border border-gray-200 bg-white rounded-lg p-6 text-gray-700">
                            No hay tratamientos registrados.
                        </div>
                    @else
                        <div class="mb-4 text-sm text-gray-600">
                            Se muestran los tratamientos accesibles según los pacientes asociados al profesional autenticado.
                        </div>

                        <div class="space-y-4">
                            @foreach($tratamientos as $tratamiento)
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
                                    $lineasCount = $tratamiento->lineasTratamiento?->count() ?? 0;

                                    $paciente = $tratamiento->paciente;
                                    $pacienteNuhsa = optional($paciente)->nuhsa ?? null;
                                    $pacienteCodigo = $pacienteNuhsa
                                        ? '#' . substr($pacienteNuhsa, -6)
                                        : 'Sin paciente';

                                    $medicoNombre = optional(optional(optional($tratamiento->medico)->user))->name ?? '-';

                                    $estadoTratamiento = $tratamiento->activo ?? null;
                                @endphp

                                <div class="bg-white border border-gray-200 rounded-lg px-5 py-4 hover:bg-gray-50 shadow-sm">

                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                                        {{-- BLOQUE PRINCIPAL --}}
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <h4 class="text-base font-semibold text-blue-800 break-words">
                                                    {{ $tratamiento->tratamiento ?? '-' }}
                                                </h4>

                                                @if($estadoTratamiento === true || $estadoTratamiento === 1)
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                        Activo
                                                    </span>
                                                @elseif($estadoTratamiento === false || $estadoTratamiento === 0)
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                                        Cerrado
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                                        Sin estado
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                @if(Auth::user()->es_medico)
                                                    <div>
                                                        <p class="text-xs uppercase tracking-wide text-gray-500">
                                                            Paciente
                                                        </p>
                                                        <p class="font-semibold text-gray-800">
                                                            {{ $pacienteCodigo }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <div>
                                                    <p class="text-xs uppercase tracking-wide text-gray-500">
                                                        Fecha
                                                    </p>
                                                    <p class="text-gray-800">
                                                        {{ $fechaAsignacion ? $fechaAsignacion->format('d/m/Y') : '-' }}
                                                    </p>
                                                </div>

                                                <div>
                                                    <p class="text-xs uppercase tracking-wide text-gray-500">
                                                        Líneas
                                                    </p>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $lineasCount }}
                                                    </p>
                                                </div>

                                                <div>
                                                    <p class="text-xs uppercase tracking-wide text-gray-500">
                                                        Duración
                                                    </p>
                                                    <p class="text-gray-800">
                                                        {{ is_null($duracionTotal) ? '-' : ((int) $duracionTotal . ' días') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ACCIONES --}}
                                        {{-- ACCIONES --}}
<div class="flex items-center gap-4 shrink-0 lg:pt-1">
    <a href="{{ route('tratamientos.show', $tratamiento->id) }}"
       class="text-blue-600 hover:text-blue-800 underline text-sm">
        Ver detalle
    </a>

    @if(Auth::user()->es_medico)
        <a href="{{ route('tratamientos.edit', $tratamiento->id) }}"
           class="text-yellow-600 hover:text-yellow-700 underline text-sm">
            Editar
        </a>

        <form method="POST"
              action="{{ route('tratamientos.destroy', $tratamiento->id) }}"
              onsubmit="return confirm('¿Eliminar este tratamiento?')"
              class="inline-flex m-0 p-0">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="text-red-600 hover:text-red-800 underline text-sm leading-none">
                Eliminar
            </button>
        </form>
    @endif
</div>
                                    </div>

                                    <div class="mt-4 pt-3 border-t">
                                        <div class="flex flex-wrap gap-3 text-xs text-gray-600">
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700">
                                                Plan terapéutico asociado al paciente
                                            </span>

                                            @if($lineasCount > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-gray-50 text-gray-700">
                                                    Líneas de tratamiento registradas
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-50 text-yellow-700">
                                                    Sin líneas registradas
                                                </span>
                                            @endif

                                            @if(Auth::user()->es_paciente)
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-gray-50 text-gray-700">
                                                    Médico: {{ $medicoNombre }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        {{-- PAGINACIÓN --}}
                        <div class="pt-6">
                            {{ $tratamientos->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-medico-layout>