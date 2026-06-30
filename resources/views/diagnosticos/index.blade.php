<x-medico-layout>
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                {{-- CABECERA --}}
                <div
                    class="p-6 bg-blue-800 text-white flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h3 class="text-xl font-semibold">Gestión de diagnósticos</h3>
                        <p class="text-sm text-blue-100 mt-1">
                            Diagnósticos clínicos asociados a los pacientes del médico.
                        </p>
                    </div>

                    @can('create', \App\Models\Diagnostico::class)
                        <a href="{{ route('diagnosticos.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                            
                        </a>
                    @endcan
                </div>

                {{-- LISTADO --}}
                <div class="p-6 bg-gray-50">
                    @if($diagnosticos->count() === 0)
                        <div class="border border-gray-200 bg-white rounded-lg p-6 text-gray-700">
                            No hay diagnósticos registrados.
                        </div>
                    @else

                        <div class="mb-4 text-sm text-gray-600">
                            Se muestran los diagnósticos accesibles según los pacientes asociados al profesional
                            autenticado.
                        </div>

                        <div class="space-y-3">
                            @foreach ($diagnosticos as $diagnostico)
                                            <div class="bg-white border rounded-lg px-5 py-4 hover:bg-gray-50 shadow-sm">

                                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">

                                                    {{-- PACIENTE --}}
                                                    <div class="md:col-span-1">
                                                        <p class="text-xs uppercase tracking-wide text-gray-500">Paciente</p>
                                                        <p class="font-semibold text-gray-800">
                                                            #{{ substr($diagnostico->paciente?->nuhsa ?? '000000', -6) }}
                                                        </p>
                                                    </div>

                                                    {{-- FECHA --}}
                                                    <div>
                                                        <p class="text-xs uppercase tracking-wide text-gray-500">Fecha</p>
                                                        <p class="text-gray-800">
                                                            {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}
                                                        </p>
                                                    </div>

                                                    {{-- TIPO --}}
                                                    <div>
                                                        <p class="text-xs uppercase tracking-wide text-gray-500">Tipo EICH</p>
                                                        <p class="font-semibold text-blue-800">
                                                            {{ $diagnostico->tipo_enfermedad ?? '-' }}
                                                        </p>
                                                    </div>

                                                    {{-- GRADO --}}
                                                    <div>
                                                        <p class="text-xs uppercase tracking-wide text-gray-500">Grado</p>
                                                        <p class="font-semibold text-gray-800">
                                                            {{ $diagnostico->grado_eich ?? '-' }}
                                                        </p>
                                                    </div>

                                                    {{-- ORIGEN --}}
                                                    <div>
                                                        <p class="text-xs uppercase tracking-wide text-gray-500">Origen</p>
                                                        <span class="inline-flex px-2 py-1 rounded-full text-xs
                                                                                                            {{ optional($diagnostico->origen)->origen === 'inferido'
                                ? 'bg-purple-100 text-purple-800'
                                : 'bg-gray-100 text-gray-700' }}">
                                                            {{ optional($diagnostico->origen)->origen ?? 'manual' }}
                                                        </span>
                                                    </div>

                                                    {{-- ACCIONES --}}
                                                    <div class="flex gap-3 md:justify-end">
                                                        <a href="{{ route('diagnosticos.show', $diagnostico) }}"
                                                            class="text-blue-600 hover:text-blue-800 underline text-sm">
                                                            Ver detalle
                                                        </a>

                                                        @can('update', $diagnostico)
                                                            <a href="{{ route('diagnosticos.edit', $diagnostico) }}"
                                                                class="text-yellow-600 hover:text-yellow-700 underline text-sm">
                                                                Editar
                                                            </a>
                                                        @endcan
                                                    </div>

                                                </div>

                                                {{-- RESUMEN INFERIDO --}}
                                                @if(optional($diagnostico->origen)->origen === 'inferido' || $diagnostico->regla_decision_id)
                                                    <div class="mt-3 pt-3 border-t text-xs text-gray-600 flex flex-wrap gap-3">
                                                        <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700">
                                                            Diagnóstico generado mediante inferencia clínica
                                                        </span>

                                                        @if($diagnostico->regla_decision_id)
                                                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-50 text-gray-700">
                                                                Regla clínica asociada
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif

                                            </div>
                            @endforeach
                        </div>

                        {{-- PAGINACIÓN --}}
                        <div class="mt-6">
                            {{ $diagnosticos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>