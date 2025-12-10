<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Paciente
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-start">

                    <div>
                        <h3 class="text-2xl font-bold">{{ $paciente->nombre }}</h3>

                        <p class="text-blue-100 mt-1">
                            NUHSA: <span class="font-semibold">{{ $paciente->nuhsa }}</span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Sexo: <span class="font-semibold">{{ $paciente->sexo }}</span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Edad:
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                            </span>
                        </p>
                    </div>

                    <div class="flex space-x-4 text-lg">
                        <a href="{{ route('pacientes.index') }}" class="hover:text-gray-200" title="Volver">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <a href="{{ route('pacientes.edit', $paciente->id) }}" class="hover:text-yellow-300"
                            title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('pacientes.destroy', $paciente->id) }}"
                            onsubmit="return confirm('¿Eliminar este paciente?')">

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

                    {{-- SECCIONES 1 y 2: DATOS PERSONALES + SOMATOMÉTRICOS EN DOS COLUMNAS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- DATOS PERSONALES --}}
                        <div class="h-full">
                            <div class="h-full flex flex-col">
                                <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                                    Datos Personales
                                </h4>

                                <div class="space-y-2 text-sm md:text-base">
                                    <p>
                                        <strong>Fecha de nacimiento:</strong>
                                        {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}
                                    </p>

                                    <p>
                                        <strong>Nº Historia / NUHSA:</strong>
                                        {{ $paciente->nuhsa }}
                                    </p>

                                    <p>
                                        <strong>Sexo:</strong>
                                        {{ $paciente->sexo }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- DATOS SOMATOMÉTRICOS --}}
                        <div class="h-full">
                            <div class="h-full flex flex-col">
                                <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                                    Datos Somatométricos
                                </h4>

                                @php
                                    $imc = null;
                                    if ($paciente->peso && $paciente->altura) {
                                        $imc = round($paciente->peso / (($paciente->altura / 100) ** 2), 1);
                                    }
                                @endphp

                                <div class="space-y-2 text-sm md:text-base">
                                    <p><strong>Peso:</strong> {{ $paciente->peso }} kg</p>
                                    <p><strong>Altura:</strong> {{ $paciente->altura }} cm</p>

                                    <p>
                                        <strong>IMC:</strong>

                                        @if($imc)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm
                                                            @if($imc < 25) bg-green-100 text-green-700
                                                            @elseif($imc < 30) bg-yellow-100 text-yellow-700
                                                            @else bg-red-100 text-red-700
                                                            @endif
                                                        ">
                                                {{ $imc }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">No disponible</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- SECCIÓN 4: DIAGNÓSTICOS DEL PACIENTE --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Diagnósticos del paciente
                        </h4>

                        @if($paciente->diagnosticos && $paciente->diagnosticos->count())
                            <div class="overflow-x-auto border rounded">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Tipo</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Origen</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Grado</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($paciente->diagnosticos as $diagnostico)
                                            <tr>
                                                <td class="px-3 py-2">
                                                    {{ optional($diagnostico->fecha_diagnostico)->format('d/m/Y') ?? '-' }}
                                                </td>
                                                <td class="px-3 py-2">
                                                    {{ $diagnostico->tipo_enfermedad ?? '-' }}
                                                </td>
                                                <td class="px-3 py-2">
                                                    @php
                                                        $origenNombre = optional($diagnostico->origen)->origen ?? null;
                                                    @endphp
                                                    <span class="px-2 py-1 rounded text-xs
                                                                                @if($origenNombre === 'inferido')
                                                                                    bg-purple-100 text-purple-800
                                                                                @elseif($origenNombre === 'manual')
                                                                                    bg-gray-100 text-gray-800
                                                                                @else
                                                                                    bg-gray-50 text-gray-600
                                                                                @endif
                                                                            ">
                                                        {{ $origenNombre ?? 'No definido' }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2">
                                                    {{ $diagnostico->grado_eich ?? '-' }}
                                                </td>
                                                <td class="px-3 py-2">
                                                    <a href="{{ route('diagnosticos.show', $diagnostico) }}"
                                                        class="text-blue-600 hover:text-blue-800 underline text-xs">
                                                        Ver detalle
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">
                                No hay diagnósticos registrados para este paciente.
                            </p>
                        @endif
                    </div>

                    {{-- SECCIÓN 3: INFORMACIÓN CLÍNICA RELACIONADA --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Información Clínica Asociada
                        </h4>

                        <ul class="list-disc ml-6 space-y-2">

                            <li>
                                <a href="{{ route('trasplantes.index') }}?paciente_id={{ $paciente->id }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver trasplantes del paciente →
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('diagnosticos.index') }}?paciente_id={{ $paciente->id }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver diagnósticos →
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('pruebas.index') }}?paciente_id={{ $paciente->id }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver pruebas clínicas →
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('sintomas.index') }}?paciente_id={{ $paciente->id }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver síntomas →
                                </a>
                            </li>

                        </ul>
                    </div>

                    {{-- SECCIÓN 5: MOTOR DE INFERENCIA CLÍNICA --}}
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Motor de inferencia clínica
                        </h4>

                        <p class="text-sm text-gray-700 mb-4">
                            La inferencia utilizará la información actual del paciente (síntomas activos,
                            órganos y scores NIH, trasplantes y otros datos clínicos relevantes) para
                            proponer un posible diagnóstico de EICH y registrar el resultado como
                            diagnóstico inferido.
                        </p>

                        {{-- Botón para lanzar inferencia --}}
                        <form method="POST" action="{{ route('diagnosticos.inferir', $paciente->id) }}">
                            @csrf

                            <button type="submit"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded shadow text-sm">
                                Ejecutar inferencia clínica
                            </button>
                        </form>

                        @php
                            $diagnosticosPaciente = $paciente->diagnosticos ?? collect();
                            $diagnosticosInferidos = $diagnosticosPaciente->filter(function ($d) {
                                return optional($d->origen)->origen === 'inferido';
                            });
                            $ultimoInferido = $diagnosticosInferidos
                                ->sortByDesc('fecha_diagnostico')
                                ->first();
                        @endphp

                        @if($ultimoInferido)
                                            <div class="mt-4 p-3 bg-white border rounded">
                                                <h5 class="text-sm font-semibold text-gray-800 mb-1">
                                                    Último diagnóstico inferido
                                                </h5>
                                                <p class="text-sm text-gray-700">
                                                    Fecha:
                                                    <strong>
                                                        {{ optional($ultimoInferido->fecha_diagnostico)->format('d/m/Y') ?? '-' }}
                                                    </strong>
                                                    –
                                                    Grado:
                                                    <strong>{{ $ultimoInferido->grado_eich ?? '-' }}</strong>
                                                    –
                                                    Regla aplicada:
                                                    <strong>
                                                        {{ optional($ultimoInferido->reglaDecision)->nombre
                            ?? ('ID ' . ($ultimoInferido->regla_decision_id ?? '-')) }}
                                                    </strong>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Puedes ver el detalle completo en la ficha del diagnóstico.
                                                </p>
                                                <a href="{{ route('diagnosticos.show', $ultimoInferido) }}"
                                                    class="inline-block mt-2 text-blue-600 hover:text-blue-800 underline text-xs">
                                                    Ver detalle del diagnóstico inferido
                                                </a>
                                            </div>
                        @else
                            <p class="mt-3 text-sm text-gray-600">
                                Todavía no se ha registrado ningún diagnóstico inferido para este paciente.
                            </p>
                        @endif
                    </div>


                    {{-- SECCIÓN 6: BOTONES DE ACCIÓN CLÍNICA --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Acciones Clínicas Rápidas
                        </h4>

                        <div class="flex flex-wrap gap-4">

                            <a href="{{ route('diagnosticos.create') }}?paciente_id={{ $paciente->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar diagnóstico
                            </a>

                            <a href="{{ route('trasplantes.create') }}?paciente_id={{ $paciente->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar trasplante
                            </a>

                            <a href="{{ route('pruebas.create') }}?paciente_id={{ $paciente->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar prueba clínica
                            </a>

                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar síntoma
                            </a>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>