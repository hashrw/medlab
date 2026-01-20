<div class="space-y-6">
    <div>
        <h4 class="text-lg font-semibold text-blue-700 mb-2">
            Solicitar cita
        </h4>
        <p class="text-sm text-gray-600">
            Envía una solicitud de cita. El equipo médico revisará tu petición.
        </p>
    </div>

    <div class="bg-white border rounded-lg p-6 shadow-sm">
        <form method="POST" action="{{ route('citas.store') }}" class="space-y-4">
            @csrf

            {{-- Motivo (selector principal) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Motivo
                </label>

                <select id="motivo_preset"
                        class="mt-1 block w-full border-gray-300 rounded"
                        required>
                    <option value="">Selecciona un motivo</option>
                    <option value="Seguimiento de síntomas">Seguimiento de síntomas</option>
                    <option value="Revisión de tratamiento">Revisión de tratamiento</option>
                    <option value="Revisión de diagnósticos recientes">Revisión de diagnósticos recientes</option>
                    <option value="Consulta sobre resultados de pruebas">Consulta sobre resultados de pruebas</option>
                    <option value="Solicitud de renovación/ajuste de medicación">Solicitud de renovación/ajuste de medicación</option>
                    <option value="Gestión administrativa">Gestión administrativa</option>
                    <option value="OTRO">Otro (explicar en detalle)</option>
                </select>
            </div>

            {{-- Motivo libre (solo si OTRO) --}}
            <div id="motivo_detalle_wrapper" class="hidden">
                <label class="block text-sm font-medium text-gray-700">
                    Detalle del motivo
                </label>

                <textarea id="motivo_detalle"
                          rows="4"
                          class="mt-1 block w-full border-gray-300 rounded"
                          placeholder="Describe brevemente el motivo de la cita"></textarea>
            </div>

            {{-- Campo real que se envía al backend --}}
            <input type="hidden" name="motivo" id="motivo_final">

            {{-- Preferencia con calendario --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Preferencia de fecha y hora (opcional)
                </label>

                <input type="datetime-local"
                       name="preferencia"
                       class="mt-1 block w-full border-gray-300 rounded"
                       value="{{ old('preferencia') }}">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const preset = document.getElementById('motivo_preset');
        const detalleWrap = document.getElementById('motivo_detalle_wrapper');
        const detalle = document.getElementById('motivo_detalle');
        const motivoFinal = document.getElementById('motivo_final');

        if (!preset || !detalleWrap || !detalle || !motivoFinal) return;

        preset.addEventListener('change', function () {
            const v = preset.value;

            if (v === 'OTRO') {
                detalleWrap.classList.remove('hidden');
                detalle.value = '';
                motivoFinal.value = '';
            } else {
                detalleWrap.classList.add('hidden');
                detalle.value = '';
                motivoFinal.value = v;
            }
        });

        // Asegurar que antes de enviar, el motivo final esté correcto
        preset.form.addEventListener('submit', function () {
            if (preset.value === 'OTRO') {
                motivoFinal.value = detalle.value.trim();
            }
        });
    })();
</script>
