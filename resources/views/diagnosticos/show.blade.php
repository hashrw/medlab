<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Diagnóstico
        </h2>

        <x-flash-message type="success" />
        @unless(session('tratamiento_existente_id'))
            <x-flash-message type="warning" />
        @endunless
        <x-flash-message type="error" />

        @if(session('tratamiento_existente_id'))
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded flex items-center justify-between">
                <div class="text-sm text-yellow-900">
                    Ya existe un tratamiento asociado a este diagnóstico.
                </div>

                <a href="{{ route('tratamientos.show', session('tratamiento_existente_id')) }}"
                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                    Abrir ficha del tratamiento
                </a>
            </div>
        @endif
    </x-slot>

    <div class="py-1">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold">
                            Diagnóstico
                            <span class="text-blue-100 font-medium">
                                {{ $diagnostico->tipo_enfermedad ? ' - ' . $diagnostico->tipo_enfermedad : '' }}
                            </span>
                        </h3>

                        <p class="text-blue-100 mt-1">
                            Fecha:
                            <span class="font-semibold">
                                {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}
                            </span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Grado EICH:
                            <span class="font-semibold">
                                {{ $diagnostico->grado_eich ?? '-' }}
                            </span>
                        </p>

                        @if($paciente)
                            <p class="text-blue-100 mt-1">
                                Paciente:
                                <span class="font-semibold">
                                    {{ $paciente->nuhsa ?? ('Paciente #' . $paciente->id) }}
                                </span>
                            </p>
                        @endif
                    </div>
                    @php
    $statusInforme = $ultimoInformeClinico?->status ?? null;

    $puedeGenerarTratamiento = in_array($statusInforme, ['completed', 'fallback'], true);

    $mensajeBloqueoTratamiento = match ($statusInforme) {
        'pending', 'processing' => 'Disponible al finalizar el informe clínico',
        null => 'Generar informe clínico antes de iniciar tratamiento',
        default => 'No se puede iniciar tratamiento en este estado',
    };

    $backUrl = session('diagnosticos_back_url');
@endphp
                    <div class="flex flex-wrap gap-3">

    @if($diagnostico->regla_decision_id)

        {{-- Generar informe --}}
        <form method="POST"
              action="{{ route('diagnosticos.evidencia', $diagnostico) }}"
              onsubmit="openInformeWizard()">
            @csrf
            <button type="submit"
                    class="px-3 py-2 text-xs font-medium bg-blue-600 hover:bg-blue-700 text-white rounded">
                Generar informe
            </button>
        </form>

        {{-- Tratamiento --}}
        <form method="POST"
              action="{{ route('tratamientos.inferirDesdeDiagnostico', $diagnostico) }}">
            @csrf

            @if($puedeGenerarTratamiento)
                <button type="button"
                        onclick="openTratamientoWizard()"
                        class="px-3 py-2 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded">
                    Proponer tratamiento
                </button>
            @else
                <button type="button"
                        class="px-3 py-2 text-xs font-medium bg-gray-200 text-gray-500 rounded cursor-not-allowed"
                        title="{{ $mensajeBloqueoTratamiento }}"
                        disabled>
                    Proponer tratamiento
                </button>
            @endif
        </form>

    @endif

    {{-- Volver --}}
    @if($backUrl)
        <a href="{{ $backUrl }}"
           class="px-3 py-2 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded">
            Volver
        </a>
    @elseif($paciente)
        <a href="{{ route('pacientes.show', $paciente->id) }}"
           class="px-3 py-2 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded">
            Volver
        </a>
    @else
        <a href="{{ route('diagnosticos.index') }}"
           class="px-3 py-2 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded">
            Volver
        </a>
    @endif

</div>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-8 space-y-10 text-gray-800">
                     {{-- INFORME CLÍNICO --}}
                                @include('diagnosticos.partials.clinical-report-panel', [
                                    'diagnostico' => $diagnostico,
                                    'ultimoInformeClinico' => $ultimoInformeClinico,
                                ])
                    {{-- SECCIÓN: CONTEXTO DEL PACIENTE --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Contexto del paciente
                        </h4>

                        @if($paciente)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                                <div class="space-y-2">
                                    <p><strong>ID Paciente:</strong> {{ $paciente->id }}</p>
                                    <p><strong>NUHSA:</strong> {{ $paciente->nuhsa ?? '-' }}</p>

                                    {{-- @if($paciente->usuarioAcceso)
                                    <p><strong>Nombre:</strong> {{ $paciente->usuarioAcceso->name ?? '-' }}</p>
                                    <p><strong>Email:</strong> {{ $paciente->usuarioAcceso->email ?? '-' }}</p>
                                    @endif --}}
                                </div>

                                <div class="space-y-2">
                                    <p><strong>Días desde trasplante:</strong> {{ $diasDesdeTrasplante ?? '-' }}</p>
                                    <p>
                                        <strong>Fecha último trasplante:</strong>
                                        {{ $ultimoTrasplante?->fecha_trasplante ? $ultimoTrasplante->fecha_trasplante->format('d/m/Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-600">Este diagnóstico no tiene paciente asociado.</p>
                        @endif
                    </div>

                    {{-- SECCIÓN: DIAGNÓSTICO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Diagnóstico
                        </h4>

                        @php
                            $tipo = strtolower(trim($diagnostico->tipo_enfermedad ?? ''));
                            $esCronica = in_array($tipo, ['eich crónica', 'eich cronica'], true);
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
                            <div class="space-y-2">
                                <p><strong>Fecha diagnóstico:</strong>
                                    {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}</p>
                                <p><strong>Tipo de enfermedad:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                                <p><strong>Grado EICH:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                                <p><strong>Estado del injerto:</strong> {{ $diagnostico->estado_injerto ?? '-' }}</p>
                            </div>

                            <div class="space-y-2">
                                <p><strong>Estado:</strong> {{ $diagnostico->estado?->estado ?? '-' }}</p>
                                <p><strong>Infección:</strong> {{ $diagnostico->infeccion?->nombre ?? '-' }}</p>

                                @if($esCronica)
                                    <p><strong>Escala Karnofsky:</strong> {{ $diagnostico->escala_karnofsky ?? '-' }}</p>
                                    <p><strong>Comienzo:</strong> {{ $diagnostico->comienzo?->tipo_comienzo ?? '-' }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm md:text-base">
                                <strong>Observaciones:</strong>
                            </p>
                            <p class="text-sm text-gray-700 mt-1">
                                {{ $diagnostico->observaciones ?: '-' }}
                            </p>
                        </div>

                        {{-- REGLA APLICADA --}}
                        @if($diagnostico->regla)
                            <details class="mt-6 bg-gray-50 border border-gray-200 rounded">
    <summary class="cursor-pointer px-4 py-3 flex items-center justify-between text-gray-700 font-semibold">
        <span>
            Regla clínica aplicada
            <span class="text-xs text-gray-500 font-normal">
                {{ $diagnostico->regla->nombre ?? '' }}
            </span>
        </span>

        <span class="text-xs text-gray-500">
            Ver detalle
        </span>
    </summary>

    <div class="px-4 pb-4">
        <p class="text-sm text-gray-800">
            <strong>Nombre:</strong> {{ $diagnostico->regla->nombre ?? '-' }}
        </p>

        <p class="text-sm text-gray-800">
            <strong>Prioridad:</strong> {{ $diagnostico->regla->prioridad ?? '-' }}
        </p>

        <p class="text-sm text-gray-800">
            <strong>Recomendación clínica:</strong>
            {{ $diagnostico->regla->tipo_recomendacion ?? '-' }}
        </p>

        @if(!empty($diagnostico->regla->descripcion_clinica))
            <p class="text-sm text-gray-800 mt-2">
                <strong>Descripción clínica:</strong>
                {{ $diagnostico->regla->descripcion_clinica }}
            </p>
        @endif
    </div>
</details>
                        @endif
                    </div>

                    {{-- SECCIÓN: SÍNTOMAS ASOCIADOS --}}
                    <div>
                        <details class="border border-gray-200 rounded bg-white">
                            <summary class="cursor-pointer px-4 py-3 flex items-center justify-between text-blue-700 font-semibold">
                                <span>
                                    Síntomas asociados
                                    <span class="text-xs text-gray-500 font-normal">
                                        ({{ $diagnostico->sintomas->count() }})
                                    </span>
                                </span>

                                <span class="text-xs text-gray-500">
                                    Ver detalle
                                                    </span>
                                                </summary>

                        <div class="                px-4 pb-4">
            @if($diagnostico->sintomas->isEmpty())
                                    <p class="text-sm text-gray-600">
                                    No hay síntomas asociados a este diagnóstico.
                </p>                
            @else
                            <div class="                overflow-x-auto border rounded">
                <table class                ="min-w-full text-sm">
                    <thead c                lass="bg-gray-50">

                        <tr>                

                                                                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Síntoma</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha diagnóstico</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Score NIH</th>
                                        </tr>
                                </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($diagnostico->sintomas as $sintoma)

                                                                               @php
                                                                                $fd = $sintoma->pivot->fecha_diagnostico ?? null;
                                                                                $fdFormatted = $fd ? \Illuminate\Support\Carbon::parse($fd)->format('d/m/Y') : '-';
                                                                            @endphp

                                    <tr>
                                        <td class="px-3 py-2">
                                            {{ $sintoma->sintoma ?? $sintoma->nombre ?? '-' }}
                                        </td>
                                            <td class="px-3 py-2">{{ $fdFormatted }}</td>
                                            <td class="px-3 py-2">{{ $sintoma->pivot->score_nih ?? '-' }}</td>
                                        </tr>
                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
            @endif
        </div>
    </details>
                    <div class="flex items-center justify-end mt-6">
                        @if($paciente)
                            <a href="{{ route('pacientes.show', $paciente->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                Volver a paciente
                            </a>
                        @else
                            <a href="{{ route('diagnosticos.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                    Volver
                                </a>
                        @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
</div>
@if($diagnostico->regla_decision_id)
        <div id="modal-tratamiento-wizard"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-blue-700 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold">Iniciar tratamiento</h3>
                    <p class="text-xs text-blue-100">
                            Se inferirá el tratamiento a partir del diagnóstico actual.
                    </p>
                </div>
                <button type="button" onclick="closeTratamientoWizard()"
                    class="text-white hover:text-gray-200 text-xl leading-none">
                        &times;
                </button>
            </div>
            <div class="px-6 py-4 space-y-4 text-sm text-gray-800">
                <div class="border rounded p-3 bg-gray-50">
                    <p><strong>Diagnóstico:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                        <p><strong>Grado:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                    <p><strong>Regla:</strong> {{ $diagnostico->regla?->nombre ?? '-' }}</p>
                </div>
                <p class="text-gray-700">
                        Al confirmar, el sistema creará un tratamiento y lo mostrará en su ficha.
                </p>
                </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end items-center gap-4">

                    <button type="button" onclick="closeTratamientoWizard()"
                    class="inline-flex items-center gap-2 px-5 h-11 rounded-md border border-blue-300
                                                                                                                                                   text-blue-700 bg-white hover:bg-blue-50
                                                                                                                                                   transition font-medium">
                        <i class="fas fa-times text-sm"></i>
                    <span>Cancelar</span>
                </button>
                <form method="POST" action="{{ route('tratamientos.inferirDesdeDiagnostico', $diagnostico) }}"
                    class="m-0">
                        @csrf
                        <button type="submit"
                        class="inline-flex items-center gap-2 px-5 h-11 rounded-md
                                                                                                                                                       bg-green-600 hover:bg-green-700
                                                                                                                                                       text-white transition font-medium shadow-sm">
                        <i class="fas fa-check text-sm"></i>
                            <span>Confirmar e iniciar</span>
                    </button>
                    </form>

            </div>

        </div>
    </div>
    <script>
        function openTratamientoWizard() {
                const m = document.getElementById('modal-tratamiento-wizard');
            if (m) m.classList.remove('hidden');
        }
        function closeTratamientoWizard() {
            const m = document.getElementById('modal-tratamiento-wizard');
                if (m) m.classList.add('hidden');
            }
        </script>
@endif
    <div id="modal-informe-wizard"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">

            <div class="px-6 py-4 bg-blue-800 text-white">
                <h3 class="text-lg font-semibold">Generando informe clínico</h3>
                <p class="text-xs text-blue-100 mt-1">
                    El sistema está procesando el caso clínico con apoyo DSS-RAG.
                </p>
            </div>

            <div class="px-6 py-6 space-y-4 text-sm text-gray-800">
                <div class="flex items-center gap-3">
                    <div class="h-4 w-4 rounded-full border-2 border-blue-600 border-t-transparent animate-spin"></div>
                    <p class="font-semibold">Proceso iniciado</p>
                </div>

                <div class="space-y-3 border-l-2 border-blue-100 ml-2 pl-5">
                    <div>
                        <p class="font-semibold text-gray-700">1. Analizando caso clínico</p>
                        <p class="text-xs text-gray-500">
                            Se está preparando la información estructurada del diagnóstico.
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-700">2. Consultando corpus científico</p>
                        <p class="text-xs text-gray-500">
                            El microservicio RAG recupera evidencia clínica relacionada.
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-700">3. Generando informe médico</p>
                        <p class="text-xs text-gray-500">
                            El informe se estructurará para facilitar la revisión médica.
                        </p>
                    </div>
                </div>
                <div class="p-3 bg-blue-50 border border-blue-100 rounded text-xs text-blue-900">
                    La generación continuará en segundo plano. Puede seguir revisando la ficha clínica.                </div>
                </div>
        </div>
    </div>
        
    <script>
                function openInformeWizard() {
            const modal = document.getElementById('modal-informe-wizard');
        
    if (!modal) {
                return;
    }
        
            modal.classList.remove('hidden');
        
            setTimeout(function () {
        modal.classList.add('hidden');
            }, 3000);
        }

        function closeInformeWizard() {
            const modal = document.getElementById('modal-informe-wizard');
        
            if (modal) {
            modal.classList.add('hidden');
            }
}
            function openClinicalReportModal() {
            const modal = document.getElementById('clinical-report-modal');
        
            if (modal) {
                modal.classList.remove('hidden');
        modal.classList.add('flex');
        }
    }

        function closeClinicalReportModal() {
            const modal = document.getElementById('clinical-report-modal');
    
            if (modal) {
                modal.classList.add('hidden');
            modal.classList.remove('flex');
            }
        }
    
        async function pollClinicalReport(statusUrl, interval = null) {
            try {
                const response = await fetch(statusUrl, {
                    headers: {
                        'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                    }
                });
    
                if (!response.ok) {
                console.error('Error HTTP consultando informe clínico:', response.status);
                    return;
            }
    
            const data = await response.json();
    
            console.log('Estado informe clínico:', data.status, data);
    
                const panel = document.getElementById('clinical-report-panel');
    
            if (panel && data.html) {
                    panel.outerHTML = data.html;
                }
    
                if (['completed', 'fallback', 'failed'].includes(data.status)) {
                if (interval) {
                        clearInterval(interval);
                    }
    
                    closeInformeWizard();
                }
            } catch (error) {
            console.error('Error consultando estado del informe clínico:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
        let statusUrl = null;

            @if(session('informe_clinico_id'))
                statusUrl = @json(route('informes-clinicos.estado', session('informe_clinico_id')));
                openInformeWizard();
            @else
            const panel = document.getElementById('clinical-report-panel');

                if (panel && ['pending', 'processing'].includes(panel.dataset.status)) {
                statusUrl = panel.dataset.statusUrl;
            }
        @endif
    
        if (!statusUrl) {
                return;
        }
    
            pollClinicalReport(statusUrl);
    
            const interval = setInterval(function () {
            pollClinicalReport(statusUrl, interval);
            }, 3000);
    });

</script>
</x-medico-layout>
