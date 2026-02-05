<x-medico-layout>
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Módulo de Diagnósticos</h3>

                    @can('create', \App\Models\Diagnostico::class)
                        <a href="{{ route('diagnosticos.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            + Nuevo Diagnóstico
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
                        <div class="space-y-2">
    @foreach ($diagnosticos as $diagnostico)
        <div class="bg-white border rounded-lg px-4 py-3 flex items-center gap-4 hover:bg-gray-50">

            {{-- FECHA --}}
            <div class="w-28 text-sm text-gray-600 shrink-0">
                {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}
            </div>

            {{-- TIPO --}}
            <div class="flex-1 font-semibold text-blue-800">
                {{ $diagnostico->tipo_enfermedad ?? 'Diagnóstico' }}
            </div>

            {{-- ORIGEN --}}
            <div class="w-28 text-xs">
                <span class="px-2 py-1 rounded-full
                    {{ optional($diagnostico->origen)->origen === 'inferido'
                        ? 'bg-purple-100 text-purple-800'
                        : 'bg-gray-100 text-gray-700' }}">
                    {{ optional($diagnostico->origen)->origen ?? 'manual' }}
                </span>
            </div>

            {{-- ESTADO --}}
            <div class="w-36 text-xs text-gray-700">
                {{ $diagnostico->estado?->nombre ?? 'Sin registro' }}
            </div>

            {{-- ACCIONES --}}
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('diagnosticos.show', $diagnostico) }}"
                   class="text-blue-600 hover:underline text-sm">
                    Ver
                </a>

                @can('update', $diagnostico)
                    <a href="{{ route('diagnosticos.edit', $diagnostico) }}"
                       class="text-yellow-600 hover:underline text-sm">
                        Editar
                    </a>
                @endcan
            </div>
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
