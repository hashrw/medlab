{{-- resources/views/pacientes/show.blade.php --}}

<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Paciente
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @php
                $w = session('warning');
                $ctx = session('flash_ctx', []);

                // OJO: $paciente->sintomas ya viene filtrado a activos por la relación
                $sintomasActivosIds = ($paciente->sintomas ?? collect())->pluck('id')->map(fn($v) => (int)$v)->all();
                // Órganos con síntomas activos (para no evaluar NIH de órganos “vacíos”)
                $organosConSintomasActivos = ($paciente->sintomas ?? collect())
    ->pluck('organo_id')
    ->filter()
    ->unique()
    ->map(fn($v) => (int)$v)
    ->values()
    ->all();

// Scores NIH ya guardados en organo_paciente
$scoresNihActuales = ($paciente->organos ?? collect())->mapWithKeys(function ($o) {
    return [(int)$o->id => ($o->pivot->score_nih ?? null)];
});

            @endphp

            <x-flash-message type="success" />

            @if($w && $w !== 'diagnostico_ya_existe')
                <x-flash-message type="warning" />
            @endif

            <x-flash-message type="error" />

            {{-- AVISO: diagnóstico ya existente (idempotencia) --}}
            @if($w === 'diagnostico_ya_existe' && !empty($ctx['diagnostico_id']))
                <div class="mb-4 border border-yellow-200 bg-yellow-50 text-yellow-900 rounded-lg p-4 flex items-start justify-between gap-4">
                    <div class="text-sm">
                        <p class="font-semibold">Ya existe un diagnóstico inferido para este paciente.</p>
                        @if(!empty($ctx['grado_eich']))
                            <p class="mt-1">Grado: <span class="font-semibold">{{ $ctx['grado_eich'] }}</span></p>
                        @endif
                    </div>

                    <div class="shrink-0">
                        <a href="{{ route('diagnosticos.show', (int) $ctx['diagnostico_id']) }}"
                           class="inline-flex items-center px-3 py-2 rounded bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold">
                            Abrir diagnóstico
                        </a>
                    </div>
                </div>
            @endif

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

                    {{-- BOTONES --}}
                    <div class="flex items-start space-x-4 text-lg self-start pt-1">
                        @php $backUrl = session('pacientes_back_url'); @endphp

                        @if($backUrl)
                            <a href="{{ $backUrl }}" class="hover:text-gray-200" title="Volver">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @else
                            <a href="{{ route('pacientes.index') }}" class="hover:text-gray-200" title="Volver a pacientes">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @endif

                        @can('update', $paciente)
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="hover:text-yellow-300" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan

                        @can('delete', $paciente)
                            <form method="POST" action="{{ route('pacientes.destroy', $paciente->id) }}"
                                  onsubmit="return confirm('¿Eliminar este paciente?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="hover:text-red-300 align-top" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-8 space-y-10 text-gray-800">

                    {{-- SECCIONES 1 y 2 --}}
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
                                            <span class="text-gray-500">No disponible</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- SECCIÓN 4: DIAGNÓSTICOS --}}
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
                                                    {{ $diagnostico->fecha_diagnostico ? \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('d/m/Y') : '-' }}
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
                                                        @endif">
                                                        {{ $origenNombre ?? 'No definido' }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2">
                                                    {{ $diagnostico->grado_eich ?? '-' }}
                                                </td>
                                                <td class="px-3 py-2">
                                                    <button type="button" onclick="openDiagnosticoModal({{ $diagnostico->id }})"
                                                        class="text-blue-600 hover:text-blue-800 underline text-xs">
                                                        Ver detalle
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- MODALES DE DETALLE --}}
                            @foreach($paciente->diagnosticos as $diagnostico)
                                <div id="modal-diagnostico-{{ $diagnostico->id }}"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden">

                                        <div class="px-6 py-4 bg-blue-700 text-white flex justify-between items-center">
                                            <div>
                                                <h3 class="text-lg font-semibold">Detalle del diagnóstico</h3>
                                                <p class="text-xs text-blue-100">
                                                    Paciente: {{ $paciente->nombre }} |
                                                    Fecha: {{ $diagnostico->fecha_diagnostico ? \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('d/m/Y') : '-' }}
                                                </p>
                                            </div>

                                            <button type="button" onclick="closeDiagnosticoModal({{ $diagnostico->id }})"
                                                class="text-white hover:text-gray-200 text-xl leading-none">
                                                &times;
                                            </button>
                                        </div>

                                        <div class="px-6 py-4 space-y-4 text-sm text-gray-800">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <h4 class="font-semibold text-gray-700 mb-1">Datos del diagnóstico</h4>
                                                    <p><strong>Tipo enfermedad:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                                                    <p><strong>Grado EICH:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                                                    <p><strong>Origen:</strong> {{ optional($diagnostico->origen)->origen ?? 'No definido' }}</p>
                                                    <p><strong>CIE-10:</strong> {{ $diagnostico->cie10 ?? '-' }}</p>
                                                </div>

                                                <div>
                                                    <h4 class="font-semibold text-gray-700 mb-1">Regla / Enfermedad asociada</h4>
                                                    <p><strong>Enfermedad:</strong> {{ optional($diagnostico->enfermedad)->nombre ?? 'No especificada' }}</p>
                                                    <p><strong>Regla clínica:</strong> {{ optional($diagnostico->regla)->nombre ?? ('ID ' . ($diagnostico->regla_decision_id ?? '-')) }}</p>

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

                                            <div>
                                                <h4 class="font-semibold text-gray-700 mb-2">Síntomas asociados</h4>

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
                                                                        <td class="px-2 py-1">{{ optional($sintoma->organo)->nombre ?? '-' }}</td>
                                                                        <td class="px-2 py-1">{{ $sintoma->sintoma ?? '-' }}</td>
                                                                        <td class="px-2 py-1">{{ $sintoma->manif_clinica ?? '-' }}</td>
                                                                        <td class="px-2 py-1">{{ $sintoma->pivot->score_nih ?? '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <p class="text-gray-600">No hay síntomas asociados a este diagnóstico.</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="px-6 py-3 bg-gray-50 flex justify-between items-center text-xs">
                                            <a href="{{ route('diagnosticos.show', $diagnostico) }}"
                                                class="text-blue-600 hover:text-blue-800 underline">
                                                Abrir ficha completa del diagnóstico
                                            </a>

                                            <button type="button" onclick="closeDiagnosticoModal({{ $diagnostico->id }})"
                                                class="px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                                                Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">No hay diagnósticos registrados para este paciente.</p>
                        @endif
                    </div>

                    {{-- SECCIÓN: INFORMACIÓN CLÍNICA ASOCIADA --}}
<div>
    <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
        Información Clínica Asociada
    </h4>

    @php
        // Clase reutilizable para botones icono (fácil de añadir más)
        $iconBtn = 'inline-flex items-center justify-center w-9 h-9 rounded border border-gray-200
                   text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition';
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Trasplantes --}}
        <div class="border rounded-lg p-4 bg-white">
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-semibold text-blue-700">Trasplantes</h5>

                <div class="flex items-center gap-2">
                    {{-- Crear (icono + tooltip) --}}
                    <a href="{{ route('pacientes.trasplantes.create', $paciente) }}"
                       class="{{ $iconBtn }}"
                       title="Registrar trasplante"
                       aria-label="Registrar trasplante">
                        <i class="fas fa-plus"></i>
                    </a>

                    {{-- Ver listado (si existe tu route trasplantes.index) --}}
                   <button type="button"
        onclick="openTrasplantesModal()"
        class="{{ $iconBtn }}"
        title="Ver trasplantes"
        aria-label="Ver trasplantes">
    <i class="fas fa-list"></i>
</button>
                </div>
            </div>

            @if(empty($trasplantes) || $trasplantes->isEmpty())
                <p class="text-sm text-gray-600">No hay trasplantes registrados.</p>
            @else
                <ul class="text-sm text-gray-700 space-y-1">
                    @foreach($trasplantes as $t)
                        <li class="flex items-center justify-between gap-3">
                            <span>
                                {{ $t->fecha_trasplante?->format('d/m/Y') ?? '-' }}
                                @if(!empty($t->tipo_trasplante))
                                    <span class="text-gray-500">— {{ $t->tipo_trasplante }}</span>
                                @endif
                            </span>

                            <a href="{{ route('trasplantes.show', $t) }}"
                               class="text-gray-500 hover:text-gray-800"
                               title="Abrir"
                               aria-label="Abrir trasplante">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Pruebas clínicas --}}
        <div class="border rounded-lg p-4 bg-white">
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-semibold text-blue-700">Pruebas Clínicas</h5>

                <div class="flex items-center gap-2">
                    {{-- IMPORTANTE: aquí va $paciente, NO $diagnostico --}}
                    <a href="{{ route('pacientes.pruebas.create', $paciente) }}"
                       class="{{ $iconBtn }}"
                       title="Registrar prueba"
                       aria-label="Registrar prueba">
                        <i class="fas fa-plus"></i>
                    </a>

                    {{-- Si tienes index de pruebas, cámbialo. Si no existe, quita este botón. --}}
                    @if(\Illuminate\Support\Facades\Route::has('pruebas.index'))
                       <button type="button"
        onclick="openPruebasModal()"
        class="{{ $iconBtn }}"
        title="Ver pruebas"
        aria-label="Ver pruebas">
    <i class="fas fa-list"></i>
</button>
                    @endif
                </div>
            </div>

            @if(empty($pruebas) || $pruebas->isEmpty())
                <p class="text-sm text-gray-600">No hay pruebas clínicas registradas.</p>
            @else
                <ul class="text-sm text-gray-700 space-y-1">
                    @foreach($pruebas as $p)
                        <li class="flex items-center justify-between gap-3">
                            <span>
                                {{ $p->fecha?->format('d/m/Y') ?? '-' }}
                                @if(!empty($p->nombre))
                                    <span class="text-gray-500">— {{ $p->nombre }}</span>
                                @endif
                            </span>

                            {{-- Si tienes show de pruebas, ajusta la ruta. Si no, elimina este link. --}}
                            @if(\Illuminate\Support\Facades\Route::has('pruebas.show'))
                                <a href="{{ route('pruebas.show', $p) }}"
                                   class="text-gray-500 hover:text-gray-800"
                                   title="Abrir"
                                   aria-label="Abrir prueba">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    <p class="mt-3 text-xs text-gray-500">
        Nota: la infección se asigna en la ficha del diagnóstico (FK en diagnósticos).
    </p>
</div>



                    {{-- SECCIÓN 5: MOTOR INFERENCIA --}}
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Motor de inferencia clínica
                        </h4>

                        <p class="text-sm text-gray-700 mb-4">
                            La inferencia utilizará la información actual del paciente (síntomas activos,
                            órganos y scores NIH) para proponer un posible diagnóstico.
                        </p>

                        {{-- BOTÓN REGISTRAR SÍNTOMAS --}}
                        <div class="flex flex-wrap gap-3 mb-4">
    <button type="button" onclick="openSintomasModal()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm shadow">
        Registrar / Actualizar síntomas
    </button>

    <button type="button" onclick="openOrganosModal()"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm shadow">
        Evaluar órganos (NIH)
    </button>
</div>

                        @if(!$tieneSintomasActivos)
                            <div class="mb-4 border border-yellow-200 bg-yellow-50 text-yellow-900 rounded-lg p-3 text-sm">
                                No es posible ejecutar la inferencia clínica: no hay síntomas activos registrados.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('diagnosticos.inferir', $paciente->id) }}">
                            @csrf

                            <button type="submit" @if(!$tieneSintomasActivos) disabled @endif
                                class="px-4 py-2 rounded shadow text-sm
                                @if($tieneSintomasActivos)
                                    bg-purple-600 hover:bg-purple-700 text-white
                                @else
                                    bg-gray-300 text-gray-600 cursor-not-allowed
                                @endif">
                                Ejecutar inferencia clínica
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- MODAL SÍNTOMAS --}}
    <div id="modal-sintomas" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden">

            <div class="px-6 py-4 bg-blue-700 text-white flex justify-between items-center">
                <h3 class="text-lg font-semibold">Registrar síntomas</h3>
                <button type="button" onclick="closeSintomasModal()" class="text-white text-xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('pacientes.sintomas.store', $paciente) }}">
                @csrf

                {{-- si quieres trazabilidad desde UI, ya va aquí --}}
                <input type="hidden" name="fuente" value="UI_MEDICO">

                <div class="p-6 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                        @foreach($sintomasCatalogo as $sintoma)
                            @php $id = (int) $sintoma->id; @endphp
                            <label class="flex items-start space-x-2 text-sm">
                                <input
                                    type="checkbox"
                                    name="sintomas[]"
                                    value="{{ $id }}"
                                    class="mt-1"
                                    @if(in_array($id, $sintomasActivosIds, true)) checked @endif
                                >
                                <span>
                                    <strong>{{ $sintoma->organo->nombre ?? '-' }}</strong><br>
                                    {{ $sintoma->sintoma }}
                                </span>
                            </label>
                        @endforeach

                    </div>
                </div>

                <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeSintomasModal()" class="px-3 py-1 border rounded text-gray-700">
                        Cancelar
                    </button>

                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">
                        Guardar síntomas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL NIH ÓRGANOS --}}
<div id="modal-organos" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-hidden">

        <div class="px-6 py-4 bg-indigo-700 text-white flex justify-between items-center">
            <h3 class="text-lg font-semibold">Evaluación NIH por órgano</h3>
            <button type="button" onclick="closeOrganosModal()" class="text-white text-xl leading-none">&times;</button>
        </div>

        <form method="POST" action="{{ route('pacientes.organosScore.store', $paciente) }}">
            @csrf

            <div class="p-6 max-h-96 overflow-y-auto space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($organosCatalogo as $organo)
                        @php
                            $oid = (int) $organo->id;
                            $tieneSintomas = in_array($oid, $organosConSintomasActivos, true);
                            $valor = $scoresNihActuales[$oid] ?? null;
                        @endphp

                        <div class="border rounded p-3 @if(!$tieneSintomas) opacity-60 @endif">
                            <div class="text-sm font-semibold text-gray-800 mb-2">
                                {{ $organo->nombre }}
                                @if(!$tieneSintomas)
                                    <span class="text-xs text-gray-500 font-normal"> (sin síntomas activos)</span>
                                @endif
                            </div>

                            <label class="text-xs text-gray-600">Score NIH</label>
                            <select name="organos[{{ $oid }}][score_nih]"
                                    class="mt-1 w-full border rounded px-2 py-1 text-sm"
                                    @if(!$tieneSintomas) disabled @endif>
                                <option value="">-- sin evaluar --</option>
                                @for($i=0; $i<=4; $i++)
                                    <option value="{{ $i }}" @if((string)$valor === (string)$i) selected @endif>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>

                            @if(!$tieneSintomas)
                                {{-- Para no “perder” el field deshabilitado --}}
                                <input type="hidden" name="organos[{{ $oid }}][score_nih]" value="">
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600">Fecha evaluación</label>
                        <input type="date" name="fecha_evaluacion"
                               value="{{ now()->toDateString() }}"
                               class="mt-1 w-full border rounded px-2 py-1 text-sm">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600">Comentario (opcional)</label>
                        <input type="text" name="comentario" maxlength="255"
                               class="mt-1 w-full border rounded px-2 py-1 text-sm"
                               placeholder="Observaciones...">
                    </div>
                </div>

            </div>

            <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-3">
                <button type="button" onclick="closeOrganosModal()"
                        class="px-3 py-1 border rounded text-gray-700">
                    Cancelar
                </button>

                <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded">
                    Guardar NIH
                </button>
            </div>

        </form>
    </div>
</div>

{{-- MODAL TRASPLANTES --}}
<div id="modal-trasplantes" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-hidden">

        <div class="px-6 py-4 bg-blue-700 text-white flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold">Trasplantes</h3>
                <p class="text-xs text-blue-100">NUHSA: {{ $paciente->nuhsa }}</p>
            </div>

            <button type="button" onclick="closeTrasplantesModal()" class="text-white text-xl leading-none">&times;</button>
        </div>

        <div class="p-6 max-h-96 overflow-y-auto text-sm text-gray-800">
            @if(empty($trasplantes) || $trasplantes->isEmpty())
                <p class="text-gray-600">No hay trasplantes registrados.</p>
            @else
                <div class="overflow-x-auto border rounded">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Tipo</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Origen</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">HLA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($trasplantes as $t)
                                <tr>
                                    <td class="px-3 py-2">{{ $t->fecha_trasplante?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $t->tipo_trasplante ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $t->origen_trasplante ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $t->identidad_hla ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="px-6 py-3 bg-gray-50 flex justify-end">
            <button type="button" onclick="closeTrasplantesModal()"
                    class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Cerrar
            </button>
        </div>
    </div>
</div>

{{-- MODAL PRUEBAS --}}
<div id="modal-pruebas" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden">

        <div class="px-6 py-4 bg-blue-700 text-white flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold">Pruebas clínicas</h3>
                <p class="text-xs text-blue-100">NUHSA: {{ $paciente->nuhsa }}</p>
            </div>

            <button type="button" onclick="closePruebasModal()" class="text-white text-xl leading-none">&times;</button>
        </div>

        <div class="p-6 max-h-96 overflow-y-auto text-sm text-gray-800">
            @if(empty($pruebas) || $pruebas->isEmpty())
                <p class="text-gray-600">No hay pruebas registradas.</p>
            @else
                @php
                    $pruebasPorTipo = $pruebas->groupBy(fn($p) => $p->tipo_prueba?->nombre ?? 'Sin tipo');
                @endphp

                <div class="space-y-6">
                    @foreach($pruebasPorTipo as $tipoNombre => $items)
                        <div class="border rounded">
                            <div class="px-4 py-2 bg-gray-50 font-semibold text-gray-700">
                                {{ $tipoNombre }}
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Nombre</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($items as $p)
                                            <tr>
                                                <td class="px-3 py-2">{{ $p->fecha?->format('d/m/Y') ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $p->nombre ?? '-' }}</td>
                                                <td class="px-3 py-2">
                                                    {{ \Illuminate\Support\Str::limit($p->resultado ?? '-', 120) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="px-6 py-3 bg-gray-50 flex justify-end">
            <button type="button" onclick="closePruebasModal()"
                    class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Cerrar
            </button>
        </div>
    </div>
</div>


    <script>
        function openDiagnosticoModal(id) {
            const modal = document.getElementById('modal-diagnostico-' + id);
            if (modal) modal.classList.remove('hidden');
        }

        function closeDiagnosticoModal(id) {
            const modal = document.getElementById('modal-diagnostico-' + id);
            if (modal) modal.classList.add('hidden');
        }

        function openSintomasModal() {
            const modal = document.getElementById('modal-sintomas');
            if (modal) modal.classList.remove('hidden');
        }

        function closeSintomasModal() {
            const modal = document.getElementById('modal-sintomas');
            if (modal) modal.classList.add('hidden');
        }
        function openOrganosModal() {
    const modal = document.getElementById('modal-organos');
    if (modal) modal.classList.remove('hidden');
}

function closeOrganosModal() {
    const modal = document.getElementById('modal-organos');
    if (modal) modal.classList.add('hidden');
}

function openTrasplantesModal() {
    const modal = document.getElementById('modal-trasplantes');
    if (modal) modal.classList.remove('hidden');
}

function closeTrasplantesModal() {
    const modal = document.getElementById('modal-trasplantes');
    if (modal) modal.classList.add('hidden');
}

function openPruebasModal() {
    const modal = document.getElementById('modal-pruebas');
    if (modal) modal.classList.remove('hidden');
}

function closePruebasModal() {
    const modal = document.getElementById('modal-pruebas');
    if (modal) modal.classList.add('hidden');
}


    </script>

    
</x-medico-layout>
