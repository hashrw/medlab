<x-medico-layout>
    <div class="py-3 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-4 bg-blue-600 text-white flex justify-between items-center rounded-t-lg">
                    <h3 class="text-lg font-semibold tracking-wide">Listado de tratamientos</h3>

                    @if(Auth::user()->es_medico)
                        <a href="{{ route('tratamientos.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
                            + Crear nuevo registro
                        </a>
                    @endif
                </div>

                {{-- CONTENIDO --}}
                <div class="p-6">

                    @if($tratamientos->isEmpty())
                        <div class="py-10 text-center text-gray-600">
                            No hay tratamientos registrados.
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            @foreach($tratamientos as $tratamiento)
                                @php
                                    // fecha_asignacion puede venir como string: parse seguro
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
                                    $pacienteNombre = optional(optional($paciente)->usuarioAcceso)->name ?? 'Paciente sin usuario';
                                    $pacienteNuhsa = optional($paciente)->nuhsa ?? '-';

                                    $medicoNombre = optional(optional(optional($tratamiento->medico)->user))->name ?? '-';
                                @endphp

                                <div class="bg-white border rounded-xl shadow hover:shadow-lg transition p-5">

                                    {{-- CABECERA CARD --}}
                                    <div class="flex justify-between items-start border-b pb-3 mb-3">
                                        <div class="pr-3">
                                            <h4 class="font-semibold text-blue-800 leading-snug">
                                                {{ $tratamiento->tratamiento ?? '-' }}
                                            </h4>

                                            <div class="mt-1 text-xs text-gray-600">
                                                <span class="font-semibold">Fecha:</span>
                                                {{ $fechaAsignacion ? $fechaAsignacion->format('d/m/Y') : 'Sin fecha' }}
                                            </div>
                                        </div>

                                        {{-- ACCIONES --}}
                                        <div class="flex space-x-3 text-gray-600">
                                            <a href="{{ route('tratamientos.show', $tratamiento->id) }}"
                                               class="text-blue-600 hover:text-blue-800"
                                               title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(Auth::user()->es_medico)
                                                <a href="{{ route('tratamientos.edit', $tratamiento->id) }}"
                                                   class="text-yellow-600 hover:text-yellow-700"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('tratamientos.destroy', $tratamiento->id) }}"
                                                      onsubmit="return confirm('¿Eliminar este tratamiento?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- CUERPO CARD --}}
                                    <div class="space-y-2 text-sm text-gray-700">

                                        <p>
                                            <span class="font-semibold">Duración total:</span>
                                            {{ is_null($duracionTotal) ? '-' : ((int) $duracionTotal . ' días') }}
                                        </p>

                                        <p>
                                            <span class="font-semibold">Líneas:</span>
                                            {{ $lineasCount }}
                                        </p>

                                        @if(Auth::user()->es_medico)
                                            <p>
                                                <span class="font-semibold">Paciente:</span>
                                                {{ $pacienteNombre }}
                                                <span class="text-gray-500">({{ $pacienteNuhsa }})</span>
                                            </p>
                                        @endif

                                        @if(Auth::user()->es_paciente)
                                            <p>
                                                <span class="font-semibold">Médico:</span>
                                                {{ $medicoNombre }}
                                            </p>
                                        @endif

                                        {{-- CTA --}}
                                        <div class="pt-2">
                                            <a href="{{ route('tratamientos.show', $tratamiento->id) }}"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                                Abrir ficha →
                                            </a>
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
