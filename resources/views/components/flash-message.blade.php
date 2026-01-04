@props(['type' => 'success'])

@php
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-800',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
        'error'   => 'bg-red-100 border-red-400 text-red-800',
    ];
@endphp

@if(session()->has($type))
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    class="border-l-4 p-4 mb-6 {{ $colors[$type] ?? $colors['success'] }}"
>
    <div class="flex justify-between items-start">
        <p class="text-sm font-medium">
           @php
    $code = session($type);
    $ctx  = session('flash_ctx', []);
@endphp

@php
    $messages = [
        'paciente_no_encontrado'    => 'Paciente no encontrado.',
        'diagnostico_inferido_ok'   => 'Diagnóstico inferido correctamente.',
        'fallback_aplicado'         => 'No se ha inferido diagnóstico: se ha aplicado la regla de fallback' .
                                       (!empty($ctx['regla_nombre']) ? ' ("' . $ctx['regla_nombre'] . '")' : '') .
                                       '. Revisar síntomas y órganos del paciente.',
        'sin_diagnostico'           => 'No se ha podido inferir ningún diagnóstico para este paciente.',
    ];
@endphp

{{ $messages[$code] ?? $code }}

        </p>

        <button
            @click="show = false"
            class="ml-4 text-lg font-bold leading-none focus:outline-none"
            aria-label="Cerrar mensaje"
        >
            ×
        </button>
    </div>
</div>
@endif
