<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Diagnóstico
        </h2>

        <x-flash-message type="success" />

        @unless (session('tratamiento_existente_id'))
            <x-flash-message type="warning" />
        @endunless

        <x-flash-message type="error" />

        @if (session('tratamiento_existente_id'))
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

    @php
        $statusInforme = $ultimoInformeClinico?->status ?? null;

        $informeEnCurso =
            $ultimoInformeClinico && in_array($ultimoInformeClinico->status, ['pending', 'processing'], true);

        $puedeConsultarEstado =
            $ultimoInformeClinico && in_array($ultimoInformeClinico->status, ['pending', 'processing'], true);

        $puedeGenerarTratamiento = $statusInforme == 'completed';
        //$puedeGenerarTratamiento = $statusInforme === 'completed';

        $mensajeBloqueoTratamiento = match ($statusInforme) {
            'pending', 'processing' => 'Disponible al finalizar el informe clínico',
            'fallback' => 'Informe de diagnóstico parcial: requiere validación médica antes de iniciar tratamiento',
            'failed' => 'No se puede iniciar tratamiento: error en la generación del informe',
            null => 'Generar informe clínico antes de iniciar tratamiento',
            default => 'No se puede iniciar tratamiento en este estado',
        };

        $backUrl = session('diagnosticos_back_url');

        $tipo = strtolower(trim($diagnostico->tipo_enfermedad ?? ''));
        $esCronica = in_array($tipo, ['eich crónica', 'eich cronica'], true);

        $estadoInformeLabel = match ($statusInforme) {
            'completed' => 'Completado',
            'fallback' => 'Parcial',
            'pending' => 'Pendiente',
            'processing' => 'En generación',
            'failed' => 'Error',
            'cancelled' => 'Cancelado',
            default => 'No generado',
        };

        $estadoInformeClass = match ($statusInforme) {
            'completed' => 'bg-green-100 text-green-800',
            'fallback' => 'bg-yellow-100 text-yellow-800',
            'pending', 'processing' => 'bg-blue-100 text-blue-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-700',
        };
    @endphp

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

                        @if ($paciente)
                            <p class="text-blue-100 mt-1">
                                Paciente:
                                <span class="font-semibold">
                                    {{ $paciente->nuhsa ?? 'Paciente #' . $paciente->id }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if ($diagnostico->regla_decision_id)
                            <form method="POST" action="{{ route('diagnosticos.evidencia', $diagnostico) }}"
                                onsubmit="openInformeWizard()">
                                @csrf

                                <button type="submit" @if ($informeEnCurso) disabled @endif
                                    class="px-3 py-2 text-xs font-medium rounded
                                        @if ($informeEnCurso) bg-gray-200 text-gray-500 cursor-not-allowed
                                        @else
                                        bg-blue-600 hover:bg-blue-700 text-white @endif">
                                    {{ $ultimoInformeClinico ? 'Regenerar informe' : 'Generar informe' }}
                                </button>
                            </form>

                            @if ($puedeConsultarEstado)
                                <button type="button" onclick="openInformeWizard()"
                                    class="px-3 py-2 text-xs font-medium bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded text-blue-800">
                                    Consultar estado
                                </button>
                            @else
                                <button type="button"
                                    class="px-3 py-2 text-xs font-medium bg-gray-200 text-gray-500 rounded cursor-not-allowed"
                                    title="No existe una generación activa" disabled>
                                    Consultar estado
                                </button>
                            @endif

                            <form method="POST"
                                action="{{ route('tratamientos.inferirDesdeDiagnostico', $diagnostico) }}">
                                @csrf

                                @if ($puedeGenerarTratamiento)
                                    <button type="button" onclick="openTratamientoWizard()"
                                        class="px-3 py-2 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded">
                                        Proponer tratamiento
                                    </button>
                                @else
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium bg-gray-200 text-gray-500 rounded cursor-not-allowed"
                                        title="{{ $mensajeBloqueoTratamiento }}" disabled>
                                        Proponer tratamiento
                                    </button>
                                @endif
                            </form>
                        @endif

                        @if ($backUrl)
                            <a href="{{ $backUrl }}" class="hover:text-gray-200 text-lg" title="Volver">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @elseif($paciente)
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="hover:text-gray-200 text-lg"
                                title="Volver">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @else
                            <a href="{{ route('diagnosticos.index') }}" class="hover:text-gray-200 text-lg"
                                title="Volver">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-6 space-y-5 text-gray-800">

                    {{-- INFORME CLÍNICO --}}
                    @include('diagnosticos.partials.clinical-report-panel', [
                        'ultimoInformeClinico' => $ultimoInformeClinico,
                    ])

                    {{-- RESUMEN CLÍNICO --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-stretch">
                        <div class="h-full border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-3">
                                Diagnóstico
                            </h4>

                            <div class="space-y-2 text-sm">
                                <p><strong>Tipo:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                                <p><strong>Grado EICH:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                                <p><strong>Estado injerto:</strong> {{ $diagnostico->estado_injerto ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="h-full border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-3">
                                Estado clínico
                            </h4>

                            <div class="space-y-2 text-sm">
                                <p><strong>Estado:</strong> {{ $diagnostico->estado?->estado ?? '-' }}</p>
                                <p><strong>Infección:</strong> {{ $diagnostico->infeccion?->nombre ?? '-' }}</p>
                                @if ($esCronica)
                                    <p><strong>Karnofsky:</strong> {{ $diagnostico->escala_karnofsky ?? '-' }}</p>
                                    <p><strong>Comienzo:</strong> {{ $diagnostico->comienzo?->tipo_comienzo ?? '-' }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="h-full border border-blue-200 rounded-lg p-4 bg-blue-50">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wide mb-3">
                                Trasplante
                            </h4>

                            <div class="space-y-2 text-sm">
                                <p><strong>Días desde trasplante:</strong></p>
                                <p class="text-3xl font-bold text-blue-900">
                                    {{ $diasDesdeTrasplante ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- BLOQUES PLEGABLES --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <details class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <summary
                                class="px-4 py-3 bg-gray-50 flex items-center justify-between cursor-pointer list-none">
                                <div>
                                    <h4 class="font-semibold text-blue-700">
                                        Detalle clínico
                                    </h4>

                                    <p class="text-sm text-gray-600">
                                        Datos completos.
                                    </p>
                                </div>

                                <span class="text-sm text-blue-700 font-semibold">
                                    Ver
                                </span>
                            </summary>

                            <div class="p-4 text-sm bg-white border-t border-gray-100 space-y-2">
                                <p><strong>ID paciente:</strong> {{ $paciente?->id ?? '-' }}</p>
                                <p><strong>Fecha diagnóstico:</strong>
                                    {{ $diagnostico->fecha_diagnostico?->format('d/m/Y') ?? '-' }}</p>
                                <p><strong>Tipo enfermedad:</strong> {{ $diagnostico->tipo_enfermedad ?? '-' }}</p>
                                <p><strong>Grado EICH:</strong> {{ $diagnostico->grado_eich ?? '-' }}</p>
                                <p><strong>Estado injerto:</strong> {{ $diagnostico->estado_injerto ?? '-' }}</p>
                                <p><strong>Estado:</strong> {{ $diagnostico->estado?->estado ?? '-' }}</p>
                                <p><strong>Infección:</strong> {{ $diagnostico->infeccion?->nombre ?? '-' }}</p>
                                @if ($diagnostico->observaciones)
                                    <p>
                                        <strong>Observaciones:</strong>
                                        {{ $diagnostico->observaciones }}
                                    </p>
                                @endif
                                @if ($esCronica)
                                    <p><strong>Karnofsky:</strong> {{ $diagnostico->escala_karnofsky ?? '-' }}</p>
                                    <p><strong>Comienzo:</strong> {{ $diagnostico->comienzo?->tipo_comienzo ?? '-' }}
                                    </p>
                                @endif
                            </div>
                        </details>
                        @if ($diagnostico->regla)
                            <details class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                                <summary
                                    class="px-4 py-3 bg-gray-50 flex items-center justify-between cursor-pointer list-none">
                                    <div>
                                        <h4 class="font-semibold text-blue-700">
                                            Regla clínica
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            Motor de inferencia.
                                        </p>
                                    </div>
                                    <span class="text-sm text-blue-700 font-semibold">
                                        Ver
                                    </span>
                                </summary>
                                <div class="p-4 text-sm bg-white border-t border-gray-100 space-y-2">
                                    <p><strong>Nombre:</strong> {{ $diagnostico->regla->nombre ?? '-' }}</p>
                                    <p><strong>Prioridad:</strong> {{ $diagnostico->regla->prioridad ?? '-' }}</p>
                                    <p><strong>Recomendación:</strong>
                                        {{ $diagnostico->regla->tipo_recomendacion ?? '-' }}</p>
                                    @if (!empty($diagnostico->regla->descripcion_clinica))
                                        <p><strong>Descripción:</strong> {{ $diagnostico->regla->descripcion_clinica }}
                                        </p>
                                    @endif
                                </div>
                            </details>
                        @endif

                        <details class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <summary
                                class="px-4 py-3 bg-gray-50 flex items-center justify-between cursor-pointer list-none">
                                <div>
                                    <h4 class="font-semibold text-blue-700">
                                        Síntomas
                                        <span class="text-xs text-gray-500 font-normal">
                                            ({{ $diagnostico->sintomas->count() }})
                                        </span>
                                    </h4>

                                    <p class="text-sm text-gray-600">
                                        Síntomas vinculados.
                                    </p>
                                </div>

                                <span class="text-sm text-blue-700 font-semibold">
                                    Ver
                                </span>
                            </summary>

                            <div class="p-4 bg-white border-t border-gray-100">
                                @if ($diagnostico->sintomas->isEmpty())
                                    <p class="text-sm text-gray-600">
                                        No hay síntomas asociados a este diagnóstico.
                                    </p>
                                @else
                                    <div class="overflow-x-auto border rounded">
                                        <table class="min-w-full text-sm">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left font-semibold text-gray-600">Síntoma
                                                    </th>
                                                    <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha
                                                    </th>
                                                    <th class="px-3 py-2 text-left font-semibold text-gray-600">Score
                                                        NIH</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach ($diagnostico->sintomas as $sintoma)
                                                    @php
                                                        $fd = $sintoma->pivot->fecha_diagnostico ?? null;
                                                        $fdFormatted = $fd
                                                            ? \Illuminate\Support\Carbon::parse($fd)->format('d/m/Y')
                                                            : '-';
                                                    @endphp
                                                    <tr>
                                                        <td class="px-3 py-2">
                                                            {{ $sintoma->sintoma ?? ($sintoma->nombre ?? '-') }}
                                                        </td>
                                                        <td class="px-3 py-2">{{ $fdFormatted }}</td>
                                                        <td class="px-3 py-2">{{ $sintoma->pivot->score_nih ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </details>
                    </div>

                    <div class="flex items-center justify-end">
                        @if ($paciente)
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
    @if ($diagnostico->regla_decision_id)
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
                        class="inline-flex items-center gap-2 px-5 h-11 rounded-md border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 transition font-medium">
                        <i class="fas fa-times text-sm"></i>
                        <span>Cancelar</span>
                    </button>
                    <form method="POST" action="{{ route('tratamientos.inferirDesdeDiagnostico', $diagnostico) }}"
                        class="m-0">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 h-11 rounded-md bg-green-600 hover:bg-green-700 text-white transition font-medium shadow-sm">
                            <i class="fas fa-check text-sm"></i>
                            <span>Confirmar e iniciar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function openTratamientoWizard() {
                const modal = document.getElementById('modal-tratamiento-wizard');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            }

            function closeTratamientoWizard() {
                const modal = document.getElementById('modal-tratamiento-wizard');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
        </script>
    @endif

    <div id="modal-informe-wizard"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">

        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">

            <div class="px-6 py-4 bg-blue-800 text-white flex justify-between items-start">

                <div>
                    <h3 class="text-lg font-semibold">
                        Estado de generación del informe clínico
                    </h3>

                    <p class="text-xs text-blue-100 mt-1">
                        Seguimiento del procesamiento de informe asociado al diagnóstico.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    @if ($ultimoInformeClinico && in_array($ultimoInformeClinico->status, ['pending', 'processing'], true))
                        <form method="POST" action="{{ route('informes.cancelar', $ultimoInformeClinico) }}"
                            onsubmit="return confirm('¿Cancelar la generación del informe clínico?')">

                            @csrf
                            <button type="submit"
                                class="px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded">
                                Cancelar
                            </button>
                        </form>
                    @endif

                    <button type="button" onclick="closeInformeWizard()"
                        class="text-white text-xl leading-none hover:text-gray-200">
                        &times;
                    </button>

                </div>
            </div>

            <div class="px-6 py-6 space-y-4 text-sm text-gray-800">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">
                        <div class="h-4 w-4 rounded-full border-2 border-blue-600 border-t-transparent animate-spin">
                        </div>

                        <p class="font-semibold">
                            Estado:
                            {{ $estadoInformeLabel }}
                        </p>
                    </div>
                    @if ($ultimoInformeClinico?->started_at)
                        <span class="text-xs text-gray-500">
                            Inicio:
                            {{ $ultimoInformeClinico->started_at->format('d/m/Y H:i:s') }}
                        </span>
                    @endif

                </div>

                <div class="space-y-3 border-l-2 border-blue-100 ml-2 pl-5">

                    <div>
                        <p class="font-semibold text-gray-700">
                            1. Analizando caso clínico
                        </p>

                        <p class="text-xs text-gray-500">
                            Se está preparando la información estructurada del diagnóstico.
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-700">
                            2. Consultando corpus científico
                        </p>

                        <p class="text-xs text-gray-500">
                            El sistema recupera evidencia clínica relacionada.
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-700">
                            3. Generando informe médico
                        </p>

                        <p class="text-xs text-gray-500">
                            El informe se estructurará para facilitar la revisión médica.
                        </p>
                    </div>

                </div>

                <div class="p-3 bg-blue-50 border border-blue-100 rounded text-xs text-blue-900">
                    @if ($ultimoInformeClinico && in_array($ultimoInformeClinico->status, ['pending', 'processing'], true))
                        La generación continúa en segundo plano. Puede seguir revisando la ficha clínica.
                    @elseif($ultimoInformeClinico?->status === 'completed')
                        El informe clínico se ha generado correctamente.
                    @elseif($ultimoInformeClinico?->status === 'fallback')
                        El informe se ha generado en modo fallback.
                    @elseif($ultimoInformeClinico?->status === 'cancelled')
                        La generación fue cancelada por el usuario.
                    @elseif($ultimoInformeClinico?->status === 'failed')
                        La generación finalizó con error.
                    @else
                        No existe una generación activa.
                    @endif

                </div>

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
        }

        function closeInformeWizard() {
            const modal = document.getElementById('modal-informe-wizard');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
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

        document.addEventListener('DOMContentLoaded', function() {
            let statusUrl = null;
            @if (session('informe_clinico_id'))
                statusUrl = @json(route('informes.estado', session('informe_clinico_id')));
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

            const interval = setInterval(function() {
                pollClinicalReport(statusUrl, interval);
            }, 3000);
        });
    </script>
</x-medico-layout>
