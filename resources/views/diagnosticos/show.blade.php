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

                    <div class="flex space-x-4 text-lg">
                        @if($diagnostico->regla_decision_id)
                            <form method="POST" action="{{ route('diagnosticos.evidencia', $diagnostico) }}"
                                onsubmit="openInformeWizard()">
                                @csrf
                                <button type="submit" class="hover:text-cyan-300" title="Generar informe clínico">
                                    <i class="fas fa-brain"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tratamientos.inferirDesdeDiagnostico', $diagnostico) }}">
                                @csrf
                                <button type="button" onclick="openTratamientoWizard()" class="hover:text-green-300"
                                    title="Iniciar tratamiento">
                                    <i class="fas fa-notes-medical"></i>
                                </button>
                            </form>
                        @endif

                        {{-- @if($paciente)
                        <a href="{{ route('pacientes.show', $paciente->id) }}" class="hover:text-gray-200"
                            title="Volver al paciente">
                            <i class="fas fa-arrow-left"></i>
                        </a> --}}
                        @php $backUrl = session('diagnosticos_back_url'); @endphp

                        @if($backUrl)
                            <a href="{{ $backUrl }}" class="hover:text-gray-200" title="Volver">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @elseif($paciente)
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="hover:text-gray-200"
                                title="Volver al paciente">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @else
                            <a href="{{ route('diagnosticos.index') }}" class="hover:text-gray-200"
                                title="Volver a diagnósticos">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @endif

                        <a href="{{ route('diagnosticos.edit', $diagnostico->id) }}" class="hover:text-yellow-300"
                            title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('diagnosticos.destroy', $diagnostico->id) }}"
                            onsubmit="return confirm('¿Eliminar este diagnóstico?')">
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
                            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded">
                                <h5 class="text-md font-semibold text-gray-700 mb-2">Regla aplicada</h5>

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
                                        <strong>Descripción clínica:</strong> {{ $diagnostico->regla->descripcion_clinica }}
                                    </p>
                                @endif
                                {{-- EVIDENCIA CIENTÍFICA RAG --}}
                                {{-- INFORME CLÍNICO DSS-RAG --}}
                                @php
                                    $clinicalReport = $ultimoInformeClinico?->clinical_report;
                                    $traceability = $ultimoInformeClinico?->traceability ?? [];
                                    $diagnosticoDss = $clinicalReport['diagnostico_dss'] ?? [];
                                    $evidenciaCientifica = $clinicalReport['evidencia_cientifica'] ?? [];
                                @endphp

                                <div class="mt-6 p-5 bg-blue-50 border border-blue-200 rounded">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h5 class="text-md font-semibold text-blue-800">
                                                Informe clínico DSS-RAG
                                            </h5>

                                            <p class="text-xs text-gray-600 mt-1">
                                                Informe generado para apoyar la validación médica del diagnóstico.
                                            </p>

                                            @if($ultimoInformeClinico)
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Estado:
                                                    <span class="font-semibold">{{ $ultimoInformeClinico->status }}</span>
                                                </p>
                                            @endif
                                        </div>

                                        @if($ultimoInformeClinico && in_array($ultimoInformeClinico->status, ['pending', 'processing'], true))
                                            <span
                                                class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">Generando</span>
                                        @elseif($ultimoInformeClinico && $ultimoInformeClinico->status === 'completed')
                                            <span
                                                class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">Completado</span>
                                        @elseif($ultimoInformeClinico && $ultimoInformeClinico->status === 'fallback')
                                            <span
                                                class="text-xs px-2 py-1 rounded bg-orange-100 text-orange-800">Fallback</span>
                                        @elseif($ultimoInformeClinico && $ultimoInformeClinico->status === 'failed')
                                            <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">Fallido</span>
                                        @endif
                                    </div>

                                    @if($clinicalReport)
                                        <div class="mt-5 space-y-5 text-sm text-gray-800">

                                            @if(!empty($clinicalReport['resumen_ejecutivo']))
                                                <div class="p-4 bg-white border border-blue-100 rounded">
                                                    <p class="font-semibold text-blue-900 mb-1">Resumen ejecutivo</p>
                                                    <p>{{ $clinicalReport['resumen_ejecutivo'] }}</p>
                                                </div>
                                            @endif

                                            @if(!empty($diagnosticoDss))
                                                <div class="p-4 bg-white border border-blue-100 rounded">
                                                    <p class="font-semibold text-blue-900 mb-2">Diagnóstico inferido por DSS</p>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                                        <p><strong>Tipo:</strong> {{ $diagnosticoDss['tipo_enfermedad'] ?? '-' }}
                                                        </p>
                                                        <p><strong>Grado:</strong> {{ $diagnosticoDss['grado_eich'] ?? '-' }}</p>
                                                        <p><strong>Estado injerto:</strong>
                                                            {{ $diagnosticoDss['estado_injerto'] ?? '-' }}</p>
                                                        <p><strong>Regla:</strong> {{ $diagnosticoDss['regla_aplicada'] ?? '-' }}
                                                        </p>
                                                    </div>

                                                    @if(!empty($diagnosticoDss['interpretacion']))
                                                        <p class="mt-3 text-sm">
                                                            {{ $diagnosticoDss['interpretacion'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(!empty($clinicalReport['justificacion_clinica']))
                                                <div>
                                                    <p class="font-semibold text-blue-900 mb-2">Justificación clínica por órganos
                                                    </p>

                                                    <div class="space-y-3">
                                                        @foreach($clinicalReport['justificacion_clinica'] as $item)
                                                            <div class="p-4 bg-white border border-blue-100 rounded">
                                                                <div class="flex items-start justify-between gap-3">
                                                                    <p class="font-semibold">
                                                                        {{ $item['organo'] ?? 'Órgano no especificado' }}
                                                                        <span class="text-xs text-gray-500">
                                                                            — Score NIH: {{ $item['score_nih'] ?? '-' }}
                                                                        </span>
                                                                    </p>

                                                                    @php $alerta = strtolower($item['nivel_alerta'] ?? ''); @endphp

                                                                    @if($alerta === 'alto')
                                                                        <span
                                                                            class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">Alerta
                                                                            alta</span>
                                                                    @elseif($alerta === 'moderado')
                                                                        <span
                                                                            class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">Alerta
                                                                            moderada</span>
                                                                    @else
                                                                        <span
                                                                            class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Alerta
                                                                            baja</span>
                                                                    @endif
                                                                </div>

                                                                @if(!empty($item['hallazgos_del_caso']))
                                                                    <ul class="list-disc ml-5 mt-2 text-xs text-gray-700">
                                                                        @foreach($item['hallazgos_del_caso'] as $hallazgo)
                                                                            <li>{{ $hallazgo }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif

                                                                @if(!empty($item['relacion_con_eich']))
                                                                    <p class="mt-3 text-xs text-gray-700">
                                                                        {{ $item['relacion_con_eich'] }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if(!empty($evidenciaCientifica))
                                                <div class="p-4 bg-white border border-blue-100 rounded">
                                                    <p class="font-semibold text-blue-900 mb-2">Evidencia científica recuperada</p>

                                                    @if(!empty($evidenciaCientifica['resumen']))
                                                        <p>{{ $evidenciaCientifica['resumen'] }}</p>
                                                    @endif

                                                    @if(!empty($evidenciaCientifica['coherencia_con_el_caso']))
                                                        <p class="mt-2 text-xs text-gray-700">
                                                            <strong>Coherencia con el caso:</strong>
                                                            {{ $evidenciaCientifica['coherencia_con_el_caso'] }}
                                                        </p>
                                                    @endif

                                                    @if(!empty($evidenciaCientifica['incertidumbres']))
                                                        <div class="mt-3">
                                                            <p class="text-xs font-semibold text-gray-700">Incertidumbres:</p>
                                                            <ul class="list-disc ml-5 mt-1 text-xs text-gray-700">
                                                                @foreach($evidenciaCientifica['incertidumbres'] as $incertidumbre)
                                                                    <li>{{ $incertidumbre }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(!empty($clinicalReport['alertas_clinicas']))
                                                <div class="p-4 bg-red-50 border border-red-200 rounded">
                                                    <p class="font-semibold text-red-800">Alertas clínicas</p>
                                                    <ul class="list-disc ml-5 mt-1 text-red-800">
                                                        @foreach($clinicalReport['alertas_clinicas'] as $alerta)
                                                            <li>{{ $alerta }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(!empty($clinicalReport['limitaciones']))
                                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                                                    <p class="font-semibold text-yellow-800">Limitaciones</p>
                                                    <ul class="list-disc ml-5 mt-1 text-yellow-800">
                                                        @foreach($clinicalReport['limitaciones'] as $limitacion)
                                                            <li>{{ $limitacion }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(!empty($clinicalReport['validacion_medica_recomendada']))
                                                <div class="p-4 bg-white border border-blue-100 rounded">
                                                    <p class="font-semibold text-blue-900">Validación médica recomendada</p>
                                                    <ul class="list-disc ml-5 mt-1">
                                                        @foreach($clinicalReport['validacion_medica_recomendada'] as $recomendacion)
                                                            <li>{{ $recomendacion }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(!empty($clinicalReport['conclusion']))
                                                <div class="p-4 bg-blue-100 border border-blue-200 rounded">
                                                    <p class="font-semibold text-blue-900">Conclusión orientada a decisión</p>
                                                    <p class="mt-1">{{ $clinicalReport['conclusion'] }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($ultimoInformeClinico && in_array($ultimoInformeClinico->status, ['pending', 'processing'], true))
                                        <p class="text-sm text-gray-700 mt-4">
                                            El informe clínico se está generando. Puede permanecer en esta pantalla.
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-700 mt-4">
                                            Aún no se ha generado ningún informe clínico DSS-RAG para este diagnóstico.
                                        </p>
                                    @endif

                                    @if(($traceability['llm_used'] ?? true) === false && $ultimoInformeClinico)
                                        <div
                                            class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded text-xs text-orange-800">
                                            El informe se ha generado en modo fallback o no ha usado LLM.
                                            @if(!empty($traceability['fallback_reason']))
                                                Motivo: {{ $traceability['fallback_reason'] }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- SECCIÓN: SÍNTOMAS ASOCIADOS --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Síntomas asociados
                        </h4>

                        @if($diagnostico->sintomas->isEmpty())
                            <p class="text-sm text-gray-600">No hay síntomas asociados a este diagnóstico.</p>
                        @else
                            <div class="overflow-x-auto border rounded">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Síntoma</th>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha diagnóstico
                                            </th>
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
                                                <td class="px-3 py-2">{{ $sintoma->sintoma ?? $sintoma->nombre ?? '-' }}</td>
                                                <td class="px-3 py-2">{{ $fdFormatted }}</td>
                                                <td class="px-3 py-2">{{ $sintoma->pivot->score_nih ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

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
                    Puede tardar unos segundos porque el modelo de lenguaje se ejecuta como proceso asíncrono.
                </div>
            </div>
        </div>
    </div>

    <script>
        function openInformeWizard() {
            const modal = document.getElementById('modal-informe-wizard');
            if (modal) modal.classList.remove('hidden');
        }

        @if(session('informe_clinico_id'))
            document.addEventListener('DOMContentLoaded', function () {
                const informeId = @json(session('informe_clinico_id'));
                const statusUrl = @json(route('informes-clinicos.estado', session('informe_clinico_id')));

                openInformeWizard();

                const interval = setInterval(async function () {
                    try {
                        const response = await fetch(statusUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            return;
                        }

                        const data = await response.json();

                        if (['completed', 'fallback', 'failed'].includes(data.status)) {
                            clearInterval(interval);
                            window.location.href = data.redirect_url;
                        }
                    } catch (error) {
                        console.error('Error consultando estado del informe clínico:', error);
                    }
                }, 3000);
            });
        @endif
    </script>
</x-medico-layout>