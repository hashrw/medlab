<div class="space-y-6">
    <x-flash-message type="success" />
    <x-flash-message type="warning" />
    <x-flash-message type="error" />

    @if($errors->has('cita'))
        <div class="mb-4 border border-red-200 bg-red-50 text-red-900 rounded-lg p-4 text-sm">
            {{ $errors->first('cita') }}
        </div>
    @endif

    <div>
        <h4 class="text-lg font-semibold text-blue-700 mb-2">
            Solicitar cita
        </h4>
        <p class="text-sm text-gray-600">
            Envía una solicitud de cita. El equipo médico revisará tu petición.
        </p>
    </div>

    @if(($pendientesCount ?? 0) > 0)
        <div class="mb-4 border border-blue-200 bg-blue-50 text-blue-900 rounded-lg p-4 text-sm">
            Tu solicitud está registrada. Tienes {{ $pendientesCount }} solicitud(es) pendiente(s). Un médico revisará tu
            petición y te confirmará fecha y hora.
        </div>
    @endif

    @if(!empty($ultimasCitas) && $ultimasCitas->count())
        <div class="mb-6 border border-gray-200 bg-white rounded-lg p-4">
            <h5 class="text-sm font-semibold text-gray-800 mb-3">Últimas solicitudes</h5>

            <div class="space-y-3">
                @foreach($ultimasCitas as $c)
                    <div class="flex items-start justify-between text-sm">
                        <div>
                            <div class="font-medium text-gray-800">
                                {{ $c->motivo ?? 'Solicitud' }}
                                @if($c->motivo === 'Otro' && !empty($c->motivo_detalle))
                                    <span class="text-gray-600">- {{ $c->motivo_detalle }}</span>
                                @endif
                            </div>

                            <div class="text-gray-600">
                                Enviada: {{ $c->created_at?->format('d/m/Y H:i') ?? '-' }}
                                @if($c->preferencia_fecha_hora)
                                    · Preferencia: {{ $c->preferencia_fecha_hora->format('d/m/Y H:i') }}
                                @endif
                            </div>
                        </div>

                        <span class="px-2 py-1 rounded text-xs border
                                    @if($c->estado === 'pendiente') border-yellow-200 bg-yellow-50 text-yellow-800
                                    @elseif($c->estado === 'aceptada') border-green-200 bg-green-50 text-green-800
                                    @elseif($c->estado === 'rechazada') border-red-200 bg-red-50 text-red-800
                                    @else border-gray-200 bg-gray-50 text-gray-700
                                    @endif
                                ">
                            {{ ucfirst($c->estado ?? 'pendiente') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white border rounded-lg p-6 shadow-sm">
        <form method="POST" action="{{ route('citas.store') }}" class="space-y-4">
            @csrf

            {{-- Motivo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Motivo</label>

                <select id="motivo" name="motivo" class="mt-1 block w-full border-gray-300 rounded" required>
                    <option value="">Selecciona un motivo</option>
                    <option value="Seguimiento de síntomas" @selected(old('motivo') === 'Seguimiento de síntomas')>
                        Seguimiento de síntomas</option>
                    <option value="Revisión de tratamiento" @selected(old('motivo') === 'Revisión de tratamiento')>
                        Revisión de tratamiento</option>
                    <option value="Revisión de diagnósticos recientes" @selected(old('motivo') === 'Revisión de diagnósticos recientes')>Revisión de diagnósticos recientes</option>
                    <option value="Consulta sobre resultados de pruebas" @selected(old('motivo') === 'Consulta sobre resultados de pruebas')>Consulta sobre resultados de pruebas</option>
                    <option value="Solicitud de renovación/ajuste de medicación" @selected(old('motivo') === 'Solicitud de renovación/ajuste de medicación')>Solicitud de renovación/ajuste de medicación</option>
                    <option value="Gestión administrativa" @selected(old('motivo') === 'Gestión administrativa')>Gestión
                        administrativa</option>
                    <option value="Otro" @selected(old('motivo') === 'Otro')>Otro (explicar en detalle)</option>
                </select>

                @error('motivo')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Detalle motivo (solo si "Otro") --}}
            <div id="motivo_detalle_wrapper" class="{{ old('motivo') === 'Otro' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700">Detalle del motivo</label>

                <textarea name="motivo_detalle" id="motivo_detalle" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded"
                    placeholder="Describe brevemente el motivo de la cita">{{ old('motivo_detalle') }}</textarea>

                @error('motivo_detalle')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Preferencia --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Preferencia de fecha y hora (opcional)
                </label>

                <input type="datetime-local" name="preferencia_fecha_hora"
                    class="mt-1 block w-full border-gray-300 rounded" value="{{ old('preferencia_fecha_hora') }}" />

                @error('preferencia_fecha_hora')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const motivo = document.getElementById('motivo');
        const wrap = document.getElementById('motivo_detalle_wrapper');
        const detalle = document.getElementById('motivo_detalle');

        if (!motivo || !wrap || !detalle) return;

        const sync = () => {
            if (motivo.value === 'Otro') {
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
                detalle.value = '';
            }
        };

        motivo.addEventListener('change', sync);
        sync();
    })();
</script>