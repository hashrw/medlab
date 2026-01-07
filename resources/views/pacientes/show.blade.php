<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Paciente
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

                        <a href="{{ route('pacientes.edit', $paciente->id) }}" class="hover:text-yellow-300" title="Editar">
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

                                <div class="space-y-2 text-sm md:text-base">
                                    <p>
                                        <strong>Peso:</strong>
                                        {{ $paciente->peso ? $paciente->peso . ' kg' : 'No disponible' }}
                                    </p>

                                    <p>
                                        <strong>Altura:</strong>
                                        {{ $paciente->altura ? $paciente->altura . ' cm' : 'No disponible' }}
                                    </p>

                                    <p>
                                        <strong>IMC:</strong>

                                        @if(!is_null($paciente->imc))
                                            <span class="
                                                inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm
                                                @if($paciente->imc_categoria === 'Normal') bg-green-100 text-green-700
                                                @elseif($paciente->imc_categoria === 'Sobrepeso') bg-yellow-100 text-yellow-700
                                                @elseif($paciente->imc_categoria && str_starts_with($paciente->imc_categoria, 'Obesidad')) bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700
                                                @endif
                                            ">
                                                {{ number_format($paciente->imc, 1, '.', '') }}
                                                — {{ $paciente->imc_categoria ?? 'No clasificado' }}
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
                                                    <button type="button"
                                                        onclick="openDiagnosticoModal({{ $diagnostico->id }})"
                                                        class="text-blue-600 hover:text-blue-800 underline text-xs">
                                                        Ver detalle
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- MODALES DE DETALLE DE DIAGNÓSTICO --}}
                            @foreach($paciente->diagnosticos as $diagnostico)
                                <div id="modal-diagnostico-{{ $diagnostico->id }}"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden">

                                        {{-- CABECERA MODAL --}}
                                        <div class="px-6 py-4 bg-blue-700 text-white flex justify-between items-center">
                                            <div>
                                                <h3 class="text-lg font-semibold">
                                                    Detalle del diagnóstico
                                                </h3>
                                                <p class="text-xs text-blue-100">
                                                    Paciente: {{ $paciente->nombre }} |
                                                    Fecha:
                                                    {{ optional($diagnostico->fecha_diagnostico)->format('d/m/Y') ?? '-' }}
                                                </p>
                                            </div>

                                            <button type="button"
                                                onclick="closeDiagnosticoModal({{ $diagnostico->id }})"
                                                class="text-white hover:text-gray-200 text-xl leading-none">
                                                &times;
                                            </button>
                                        </div>

                                        {{-- CUERPO MODAL --}}
                                        <div class="px-6 py-4 space-y-4 text-sm text-gray-800">

                                            {{-- DATOS PRINCIPALES --}}
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <h4 class="font-semibold text-gray-700 mb-1">
                                                        Datos del diagnóstico
                                                    </h4>
                                                    <p><strong>Tipo enfermedad:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                                                    <p><strong>Grado EICH:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                                                    <p>
                                                        <strong>Origen:</strong>
                                                        {{ optional($diagnostico->origen)->origen ?? 'No definido' }}
                                                    </p>
                                                    <p><strong>CIE-10:</strong> {{ $diagnostico->cie10 ?? '-' }}</p>
                                                </div>

                                                <div>
                                                    <h4 class="font-semibold text-gray-700 mb-1">
                                                        Regla / Enfermedad asociada
                                                    </h4>
                                                    <p>
                                                        <strong>Enfermedad:</strong>
                                                        {{ optional($diagnostico->enfermedad)->nombre ?? 'No especificada' }}
                                                    </p>
                                                    <p>
                                                        <strong>Regla clínica:</strong>
                                                        {{ optional($diagnostico->reglaDecision)->nombre ?? ('ID ' . ($diagnostico->regla_decision_id ?? '-')) }}
                                                    </p>
                                                    @if(!empty($diagnostico->descripcion_clinica ?? $diagnostico->observaciones))
                                                        <p class="mt-1">
                                                            <strong>Resumen clínico:</strong><br>
                                                            <span class="text-gray-700">
                                                                {{ $diagnostico->descripcion_clinica ?? $diagnostico->observaciones }}
                                                            </span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- SÍNTOMAS ASOCIADOS --}}
                                            <div>
                                                <h4 class="font-semibold text-gray-700 mb-2">
                                                    Síntomas asociados
                                                </h4>

                                                @if($diagnostico->sintomas && $diagnostico->sintomas->count())
                                                    <div class="max-h-60 overflow-y-auto border rounded">
                                                        <table class="min-w-full text-xs">
                                                            <thead class="bg-gray-50">
                                                                <tr>
                                                                    <th class="px-2 py-1 text-left font-semibold text-gray-600">Órgano</th>
                                                                    <th class="px-2 py-1 text-left font-semibold text-gray-600">Síntoma</th>
                                                                    <th class="px-2 py-1 text-left font-semibold text-gray-600">Manifestación Clínica</th>
                                                                    <th class="px-2 py-1 text-left font-semibold text-gray-600">Score NIH</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-100">
                                                                @foreach($diagnostico->sintomas as $sintoma)
                                                                    <tr>
                                                                        <td class="px-2 py-1">
                                                                            {{ optional($sintoma->organo)->nombre ?? '-' }}
                                                                        </td>
                                                                        <td class="px-2 py-1">
                                                                            {{ $sintoma->sintoma ?? '-' }}
                                                                        </td>
                                                                        <td class="px-2 py-1">
                                                                            {{ $sintoma->manif_clinica ?? '-' }}
                                                                        </td>
                                                                        <td class="px-2 py-1">
                                                                            {{ $sintoma->pivot->score_nih ?? '-' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <p class="text-gray-600">
                                                        No hay síntomas asociados a este diagnóstico.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- PIE DE MODAL --}}
                                        <div class="px-6 py-3 bg-gray-50 flex justify-between items-center text-xs">
                                            <a href="{{ route('diagnosticos.show', $diagnostico) }}"
                                                class="text-blue-600 hover:text-blue-800 underline">
                                                Abrir ficha completa del diagnóstico
                                            </a>

                                            <button type="button"
                                                onclick="closeDiagnosticoModal({{ $diagnostico->id }})"
                                                class="px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">
                                No hay diagnósticos registrados para este paciente.
                            </p>
                        @endif
                    </div>

                    {{-- SECCIÓN 3: INFORMACIÓN CLÍNICA ASOCIADA --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Información Clínica Asociada
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

                            {{-- TRASPLANTES --}}
                            <div class="bg-white border rounded-lg p-4 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-2">
                                    Trasplantes
                                </h5>

                                @if($paciente->trasplantes->count())
                                    <ul class="space-y-2 text-sm">
                                        @foreach($paciente->trasplantes->sortByDesc('fecha_trasplante') as $trasplante)
                                            <li class="border-b pb-2">
                                                <p>
                                                    <strong>Fecha:</strong>
                                                    {{ $trasplante->fecha_trasplante?->format('d/m/Y') ?? '-' }}
                                                </p>
                                                <p>
                                                    <strong>Tipo:</strong> {{ $trasplante->tipo_trasplante ?? '-' }}
                                                </p>
                                                <p>
                                                    <strong>Días desde trasplante:</strong>
                                                    {{ $trasplante->dias_desde_trasplante ?? '-' }}
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-600">
                                        No hay trasplantes registrados.
                                    </p>
                                @endif
                            </div>

                            {{-- PRUEBAS CLÍNICAS --}}
                            <div class="bg-white border rounded-lg p-4 shadow-sm">
                                <h5 class="font-semibold text-blue-600 mb-2">
                                    Pruebas Clínicas
                                </h5>

                                @if($paciente->pruebas->count())
                                    <ul class="space-y-2 text-sm">
                                        @foreach($paciente->pruebas->sortByDesc('fecha') as $prueba)
                                            <li class="border-b pb-2">
                                                <p>
                                                    <strong>Fecha:</strong>
                                                    {{ $prueba->fecha?->format('d/m/Y') ?? '-' }}
                                                </p>
                                                <p>
                                                    <strong>Prueba:</strong> {{ $prueba->nombre }}
                                                </p>
                                                <p>
                                                    <strong>Tipo:</strong>
                                                    {{ optional($prueba->tipo_prueba)->nombre ?? '-' }}
                                                </p>
                                                <p class="text-gray-700">
                                                    <strong>Resultado:</strong>
                                                    {{ $prueba->resultado ?? '-' }}
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-600">
                                        No hay pruebas clínicas registradas.
                                    </p>
                                @endif
                            </div>

                        </div>
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
                            $ultimoInferido = $diagnosticosInferidos->sortByDesc('fecha_diagnostico')->first();
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
                                        {{ optional($ultimoInferido->reglaDecision)->nombre ?? ('ID ' . ($ultimoInferido->regla_decision_id ?? '-')) }}
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

                </div>
            </div>

        </div>
    </div>

    <script>
        function openDiagnosticoModal(id) {
            const modal = document.getElementById('modal-diagnostico-' + id);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeDiagnosticoModal(id) {
            const modal = document.getElementById('modal-diagnostico-' + id);
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
</x-medico-layout>
