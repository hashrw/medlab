<div class="space-y-10 text-gray-800">

    {{-- Datos personales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="h-full">
            <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                Datos personales
            </h4>

            <div class="space-y-2 text-sm md:text-base">
                <p><strong>NUHSA:</strong> {{ $paciente->nuhsa }}</p>
                <p><strong>Sexo:</strong> {{ $paciente->sexo ?? '-' }}</p>
                <p>
                    <strong>Fecha de nacimiento:</strong>
                    {{ $paciente->fecha_nacimiento?->format('d/m/Y') ?? '-' }}
                </p>
                <p>
                    <strong>Edad:</strong>
                    {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : '-' }}
                </p>
            </div>
        </div>

        <div class="h-full">
            <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                Datos somatométricos
            </h4>

            <div class="space-y-2 text-sm md:text-base">
                <p><strong>Peso:</strong> {{ $paciente->peso ? $paciente->peso . ' kg' : '-' }}</p>
                <p><strong>Altura:</strong> {{ $paciente->altura ? $paciente->altura . ' cm' : '-' }}</p>

                <p>
                    <strong>IMC:</strong>
                    @if(!is_null($paciente->imc))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm
                            @if($paciente->imc_categoria === 'Normal') bg-green-100 text-green-700
                            @elseif($paciente->imc_categoria === 'Sobrepeso') bg-yellow-100 text-yellow-700
                            @elseif($paciente->imc_categoria && str_starts_with($paciente->imc_categoria, 'Obesidad')) bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ number_format($paciente->imc, 1, '.', '') }}
                            — {{ $paciente->imc_categoria ?? 'No clasificado' }}
                        </span>
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Diagnósticos --}}
    <div>
        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
            Mis diagnósticos
        </h4>

        @php
            $diagnosticos = $paciente->diagnosticos ?? collect();
        @endphp

        @if($diagnosticos->isEmpty())
            <p class="text-gray-600">No hay diagnósticos registrados.</p>
        @else
            <div class="overflow-x-auto border rounded">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Tipo</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Origen</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Grado</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($diagnosticos->sortByDesc('fecha_diagnostico') as $dx)
                            @php
                                $origenNombre = optional($dx->origen)->origen ?? null;
                            @endphp
                            <tr>
                                <td class="px-3 py-2">{{ $dx->fecha_diagnostico?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $dx->tipo_enfermedad ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($origenNombre === 'inferido') bg-purple-100 text-purple-800
                                        @elseif($origenNombre === 'manual') bg-gray-100 text-gray-800
                                        @else bg-gray-50 text-gray-600
                                        @endif">
                                        {{ $origenNombre ?? 'No definido' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">{{ $dx->grado_eich ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('paciente.diagnosticos.show', $dx->id) }}"
                                       class="text-blue-600 hover:text-blue-800 underline text-xs">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Tratamientos --}}
    <div>
        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
            Mis tratamientos
        </h4>

        @php
            $tratamientos = $paciente->tratamientos ?? collect();
        @endphp

        @if($tratamientos->isEmpty())
            <p class="text-gray-600">No hay tratamientos registrados.</p>
        @else
            <div class="overflow-x-auto border rounded">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Tratamiento</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Estado</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tratamientos->sortByDesc('id') as $t)
                            <tr>
                                <td class="px-3 py-2">{{ $t->fecha_asignacion?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $t->tratamiento ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @php $estado = $t->estado_tratamiento ?? null; @endphp
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($estado === 'activo') bg-green-100 text-green-700
                                        @elseif($estado === 'cerrado') bg-gray-100 text-gray-700
                                        @else bg-yellow-50 text-yellow-800
                                        @endif">
                                        {{ $estado ?? 'sin_estado' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('paciente.tratamientos.show', $t->id) }}"
                                       class="text-blue-600 hover:text-blue-800 underline text-xs">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Trasplantes y Pruebas --}}
    <div>
        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
            Información clínica asociada
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded-lg p-4 shadow-sm">
                <h5 class="font-semibold text-blue-600 mb-2">Trasplantes</h5>

                @if(($paciente->trasplantes ?? collect())->isEmpty())
                    <p class="text-sm text-gray-600">No hay trasplantes registrados.</p>
                @else
                    <ul class="space-y-2 text-sm">
                        @foreach($paciente->trasplantes->sortByDesc('fecha_trasplante') as $tr)
                            <li class="border-b pb-2">
                                <p><strong>Fecha:</strong> {{ $tr->fecha_trasplante?->format('d/m/Y') ?? '-' }}</p>
                                <p><strong>Tipo:</strong> {{ $tr->tipo_trasplante ?? '-' }}</p>
                                <p><strong>Días desde trasplante:</strong> {{ $tr->dias_desde_trasplante ?? '-' }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- PRUEBAS CLÍNICAS --}}
<div class="bg-white border rounded-lg p-4 shadow-sm">
    <h5 class="font-semibold text-blue-600 mb-2">
        Pruebas clínicas
    </h5>

    @php
        $pruebas = ($paciente->pruebas ?? collect())->sortByDesc('fecha')->values();
        $total = $pruebas->count();
        $ultima = $pruebas->first();
    @endphp

    @if($total === 0)
        <p class="text-sm text-gray-600">
            No hay pruebas clínicas registradas.
        </p>
    @else
        {{-- Resumen --}}
        <div class="mb-3 text-sm text-gray-700">
            <p>
                <strong>Total:</strong> {{ $total }}
                @if($ultima)
                    <span class="mx-2 text-gray-300">|</span>
                    <strong>Última:</strong>
                    {{ $ultima->fecha?->format('d/m/Y') ?? '-' }}
                    — {{ $ultima->nombre ?? '-' }}
                @endif
            </p>
        </div>

        {{-- Listado compacto (expandible) --}}
        <div class="overflow-x-auto border rounded">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Prueba</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Tipo</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Detalle</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($pruebas as $i => $prueba)
                        @php
                            $pid = 'prueba-detalle-' . $i;
                            $tipo = optional($prueba->tipo_prueba)->nombre ?? '-';
                            $fecha = $prueba->fecha?->format('d/m/Y') ?? '-';
                            $nombre = $prueba->nombre ?? '-';
                            $resultado = $prueba->resultado ?? null;
                        @endphp

                        <tr class="align-top">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $fecha }}</td>
                            <td class="px-3 py-2">
                                <div class="font-medium text-gray-900">{{ $nombre }}</div>
                            </td>
                            <td class="px-3 py-2">{{ $tipo }}</td>
                            <td class="px-3 py-2">
                                <button type="button"
                                        class="text-blue-600 hover:text-blue-800 underline text-xs"
                                        onclick="togglePruebaDetalle('{{ $pid }}')">
                                    Ver resultado
                                </button>

                                <div id="{{ $pid }}" class="mt-2 hidden">
                                    <div class="border rounded p-3 bg-gray-50 text-xs text-gray-800 space-y-2">
                                        <p>
                                            <strong>Resultado:</strong>
                                            {{ $resultado ?: 'Sin resultado registrado.' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-3 text-xs text-gray-500">
            Nota: esta información es orientativa. Para interpretación clínica, consulta con tu equipo médico.
        </p>
    @endif
</div>
        </div>
    </div>

</div>

<script>
    function togglePruebaDetalle(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('hidden');
    }
</script>