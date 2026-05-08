<div class="bg-white shadow-md rounded-lg p-6 border border-amber-200">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Solicitudes de cita pendientes</h3>
            <p class="text-sm text-gray-600">
                Revisa y responde solicitudes de pacientes.
            </p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500">Pendientes</div>
            <div class="text-3xl font-semibold text-gray-900">
                {{ (int) ($citasPendientesCount ?? 0) }}
            </div>
        </div>
    </div>

    <div class="mt-4 flex items-center justify-between">
        <a href="{{ route('citas.index', ['estado' => 'pendiente']) }}"
            class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded">
            Ver bandeja
        </a>

        <a href="{{ route('citas.index') }}" class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
            Ver todas
        </a>
    </div>

    <div class="mt-5 border-t pt-4">
        <div class="text-sm font-semibold text-gray-800 mb-3">Últimas pendientes</div>

        @php
            $items = $citasPendientesTop ?? collect();
        @endphp

        @if($items->isEmpty())
            <div class="text-sm text-gray-500">No hay solicitudes pendientes.</div>
        @else
            <div class="space-y-3">
                @foreach($items as $c)
                    @php
                        $pacienteNombre = null;
                        if ($c->paciente) {
                            $pacienteNombre = $c->paciente->usuarioAcceso->name ?? null;
                        }

                        $pref = $c->preferencia_fecha_hora
                            ? $c->preferencia_fecha_hora->format('d/m/Y H:i')
                            : null;

                        $motivo = $c->motivo ?? 'Solicitud';
                        if (strtolower((string) $c->motivo) === 'otro' && !empty($c->motivo_detalle)) {
                            $motivo = 'Otro: ' . $c->motivo_detalle;
                        }
                    @endphp

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $pacienteNombre ?? ('Paciente #' . ($c->paciente_id ?? '-')) }}
                            </div>

                            <div class="text-sm text-gray-700">
                                {{ $motivo }}
                            </div>

                            <div class="text-xs text-gray-500">
                                Enviada: {{ $c->created_at?->format('d/m/Y H:i') ?? '-' }}
                                @if($pref)
                                    · Preferencia: {{ $pref }}
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('citas.show', $c->id) }}" class="text-sm text-blue-700 hover:underline">
                            Ver
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>