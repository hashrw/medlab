@php
    $clinicalReport = $ultimoInformeClinico?->clinical_report;
    $traceability = $ultimoInformeClinico?->traceability ?? [];

    $diagnosticoDss = $clinicalReport['diagnostico_dss'] ?? [];
    $evidenciaCientifica = $clinicalReport['evidencia_cientifica'] ?? [];
    $justificacionClinica = $clinicalReport['justificacion_clinica'] ?? [];

    $estadoInforme = $ultimoInformeClinico?->status;

    $estadoClinicoLabel = match ($estadoInforme) {
        'completed' => 'Informe generado',
        'fallback' => 'Informe generado parcialmente',
        'failed' => 'No generado',
        'pending', 'processing' => 'En generación',
        default => 'No generado',
    };

    $estadoClinicoClass = match ($estadoInforme) {
        'completed' => 'bg-green-100 text-green-800',
        'fallback' => 'bg-yellow-100 text-yellow-800',
        'failed' => 'bg-red-100 text-red-800',
        'pending', 'processing' => 'bg-blue-100 text-blue-800',
        default => 'bg-gray-100 text-gray-700',
    };

    $nivelesAlerta = collect($justificacionClinica)
        ->pluck('nivel_alerta')
        ->filter()
        ->map(fn($nivel) => strtolower($nivel));

    $alertaGlobal = $nivelesAlerta->contains('alto')
        ? 'alta'
        : ($nivelesAlerta->contains('moderado') ? 'moderada' : 'baja');

    $hallazgoLabels = [
        'o1_diarrea_con_sangre' => 'Diarrea con sangre',
        'o1_dolor_abdominal' => 'Dolor abdominal',
        'o1_nauseas' => 'Náuseas',
        'o1_vomitos' => 'Vómitos',
        'o2_hiperbilirrubinemia' => 'Hiperbilirrubinemia',
        'o2_alt_elevada' => 'ALT elevada',
        'o2_fosfatasa_alcalina_elevada' => 'Fosfatasa alcalina elevada',
    ];

    $puedeAbrirInforme = !empty($clinicalReport);
@endphp

<div id="clinical-report-panel" data-status="{{ $estadoInforme }}"
    data-status-url="{{ $ultimoInformeClinico ? route('informes-clinicos.estado', $ultimoInformeClinico) : '' }}"
    class="mt-6 bg-blue-50 border border-blue-200 rounded overflow-hidden">

    <div class="p-5 bg-white border-b border-blue-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h5 class="text-lg font-semibold text-blue-900">
                    Informe clínico asistido
                </h5>
                <p class="text-xs text-gray-600 mt-1">
                    Documento de apoyo para revisión médica del diagnóstico inferido.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs px-2 py-1 rounded {{ $estadoClinicoClass }}">
                    {{ $estadoClinicoLabel }}
                </span>

                @if($clinicalReport)
                    @if($alertaGlobal === 'alta')
                        <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">
                            Prioridad alta
                        </span>
                    @elseif($alertaGlobal === 'moderada')
                        <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                            Prioridad moderada
                        </span>
                    @else
                        <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">
                            Prioridad baja
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="p-5">
        @if($clinicalReport)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
                <div class="p-3 bg-white border border-blue-100 rounded">
                    <p class="text-gray-500">Tipo</p>
                    <p class="font-semibold text-gray-900">
                        {{ $diagnosticoDss['tipo_enfermedad'] ?? $diagnostico->tipo_enfermedad ?? '-' }}
                    </p>
                </div>

                <div class="p-3 bg-white border border-blue-100 rounded">
                    <p class="text-gray-500">Grado</p>
                    <p class="font-semibold text-gray-900">
                        {{ $diagnosticoDss['grado_eich'] ?? $diagnostico->grado_eich ?? '-' }}
                    </p>
                </div>

                <div class="p-3 bg-white border border-blue-100 rounded">
                    <p class="text-gray-500">Órganos afectados</p>
                    <p class="font-semibold text-gray-900">
                        {{ collect($justificacionClinica)->pluck('organo')->filter()->implode(', ') ?: '-' }}
                    </p>
                </div>

                <div class="p-3 bg-white border border-blue-100 rounded">
                    <p class="text-gray-500">Estado</p>
                    <p class="font-semibold text-gray-900">
                        {{ $estadoClinicoLabel }}
                    </p>
                </div>
            </div>

            @if(!empty($clinicalReport['conclusion']))
                <div class="mt-4 p-3 bg-blue-100 border border-blue-200 rounded text-sm text-blue-950">
                    <strong>Conclusión:</strong> {{ $clinicalReport['conclusion'] }}
                </div>
            @endif

            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <p class="text-xs text-gray-600">
                    El informe está disponible para revisión médica detallada.
                </p>

                <button type="button" onclick="openClinicalReportModal()"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded text-sm">
                    Abrir informe clínico
                </button>
            </div>

            @if($estadoInforme === 'fallback')
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-900">
                    El informe se ha generado con información clínica parcial. Revisar limitaciones antes de utilizarlo como
                    apoyo para decisiones posteriores.
                </div>
            @endif

        @elseif($ultimoInformeClinico && in_array($estadoInforme, ['pending', 'processing'], true))
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
                El informe clínico se está generando. Puede continuar revisando los datos del paciente.
                El resultado aparecerá automáticamente cuando finalice el procesamiento.
            </div>
        @else
            <div class="p-4 bg-white border border-blue-100 rounded text-sm text-gray-700">
                Aún no se ha generado ningún informe clínico para este diagnóstico.
            </div>
        @endif
    </div>

    @if($clinicalReport)
        <div id="clinical-report-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">

            <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">

                <div class="px-6 py-4 bg-blue-800 text-white flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Informe clínico asistido</h3>
                        <p class="text-xs text-blue-100 mt-1">
                            Revisión estructurada del diagnóstico inferido y de la evidencia clínica asociada.
                        </p>
                    </div>

                    <button type="button" onclick="closeClinicalReportModal()"
                        class="text-white hover:text-gray-200 text-xl leading-none">
                        &times;
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-5 text-sm text-gray-800">

                    @if(!empty($clinicalReport['resumen_ejecutivo']))
                        <section class="p-4 bg-white border border-blue-100 rounded">
                            <h4 class="font-semibold text-blue-900 mb-2">Resumen clínico</h4>
                            <p>{{ $clinicalReport['resumen_ejecutivo'] }}</p>
                        </section>
                    @endif

                    @if(!empty($justificacionClinica))
                        <section>
                            <h4 class="font-semibold text-blue-900 mb-3">
                                Justificación clínica por órganos
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($justificacionClinica as $item)
                                    @php
                                        $alerta = strtolower($item['nivel_alerta'] ?? '');
                                    @endphp

                                    <div class="p-4 bg-blue-50 border border-blue-100 rounded">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-semibold text-gray-900">
                                                    {{ $item['organo'] ?? 'Órgano no especificado' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Score NIH: {{ $item['score_nih'] ?? '-' }}
                                                </p>
                                            </div>

                                            @if($alerta === 'alto')
                                                <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-800">Alta</span>
                                            @elseif($alerta === 'moderado')
                                                <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">Moderada</span>
                                            @else
                                                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Baja</span>
                                            @endif
                                        </div>

                                        @if(!empty($item['hallazgos_del_caso']))
                                            <ul class="list-disc ml-5 mt-3 text-xs text-gray-700">
                                                @foreach($item['hallazgos_del_caso'] as $hallazgo)
                                                    <li>{{ $hallazgoLabels[$hallazgo] ?? $hallazgo }}</li>
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
                        </section>
                    @endif

                    @if(!empty($clinicalReport['validacion_medica_recomendada']))
                        <section class="p-4 bg-white border border-blue-100 rounded">
                            <h4 class="font-semibold text-blue-900 mb-2">Validación médica recomendada</h4>
                            <ul class="list-disc ml-5 space-y-1">
                                @foreach($clinicalReport['validacion_medica_recomendada'] as $recomendacion)
                                    <li>{{ $recomendacion }}</li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if(!empty($evidenciaCientifica))
                        <section class="p-4 bg-white border border-blue-100 rounded">
                            <h4 class="font-semibold text-blue-900 mb-2">Evidencia clínica recuperada</h4>

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
                        </section>
                    @endif

                    @if(!empty($clinicalReport['limitaciones']))
                        <section class="p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-900">
                            <h4 class="font-semibold mb-2">Limitaciones del informe</h4>
                            <ul class="list-disc ml-5 space-y-1">
                                @foreach($clinicalReport['limitaciones'] as $limitacion)
                                    <li>{{ $limitacion }}</li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if(!empty($clinicalReport['alertas_clinicas']))
                        <section class="p-4 bg-red-50 border border-red-200 rounded text-red-900">
                            <h4 class="font-semibold mb-2">Alertas clínicas</h4>
                            <ul class="list-disc ml-5 space-y-1">
                                @foreach($clinicalReport['alertas_clinicas'] as $alerta)
                                    <li>{{ $alerta }}</li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    <button type="button" onclick="closeClinicalReportModal()"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>