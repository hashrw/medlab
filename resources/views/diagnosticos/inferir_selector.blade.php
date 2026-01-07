<x-medico-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                Inferir diagnóstico
            </h3>

            <a href="{{ route('dashboard.medico') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Volver al panel
            </a>
        </div>
    </x-slot>

    <div class="mt-4 space-y-4">

        {{-- BUSCADOR --}}
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4">
            <form method="GET" action="{{ route('diagnosticos.inferirSelector') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div class="md:col-span-5">
                    <input
                        type="text"
                        name="q"
                        value="{{ $q ?? '' }}"
                        class="w-full border border-gray-300 rounded-md p-2"
                        placeholder="Buscar por ID, NUHSA o nombre"
                    />
                </div>

                <div class="md:col-span-1">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                        Buscar
                    </button>
                </div>
            </form>

            <div class="mt-3 text-xs text-gray-500">
                Consejo: busca por NUHSA para ir directo al paciente.
            </div>
        </div>

        {{-- RESULTADOS --}}
        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 font-semibold flex items-center justify-between">
                <div>Pacientes</div>
                <div class="text-sm text-gray-500">
                    {{ method_exists($pacientes, 'total') ? $pacientes->total() : ($pacientes?->count() ?? 0) }} resultados
                </div>
            </div>

            <div class="p-4">
                @forelse($pacientes as $p)
                    @php
                        $nombre = $p->nombre ?? ($p->usuarioAcceso->name ?? ('Paciente #' . $p->id));
                        $edad = $p->edad ?? null;
                        $imc = $p->imc ?? null;
                        $imcCat = $p->imc_categoria ?? null;
                    @endphp

                    <div class="border border-gray-200 rounded-lg p-4 mb-3 last:mb-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $nombre }}
                                    @if(!empty($p->nuhsa))
                                        <span class="text-gray-500 font-normal">({{ $p->nuhsa }})</span>
                                    @endif
                                </div>

                                <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs text-gray-700">
                                    <div>
                                        <span class="font-semibold">Sexo:</span>
                                        <span>{{ $p->sexo ?? '-' }}</span>
                                    </div>

                                    <div>
                                        <span class="font-semibold">Edad:</span>
                                        <span>{{ $edad ? ($edad . ' años') : '-' }}</span>
                                    </div>

                                    <div>
                                        <span class="font-semibold">IMC:</span>
                                        @if($imc)
                                            <span class="
                                                px-2 py-1 rounded-full text-xs
                                                @if($imcCat === 'Normal') bg-green-100 text-green-700
                                                @elseif($imcCat === 'Sobrepeso') bg-yellow-100 text-yellow-700
                                                @elseif(is_string($imcCat) && str_starts_with($imcCat, 'Obesidad')) bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700
                                                @endif
                                            ">
                                                {{ $imc }}{{ $imcCat ? (' — ' . $imcCat) : '' }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">No disponible</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('pacientes.show', $p) }}"
                                   class="px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                                    Abrir ficha
                                </a>

                                <form method="POST" action="{{ route('diagnosticos.inferir', $p->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded text-sm">
                                        Inferir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Sin resultados.</div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $pacientes->links() }}
            </div>
        </div>
    </div>
</x-medico-layout>
