<x-medico-layout>
    <x-slot name="header">
        <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold">Editar Diagnóstico</h3>
                @if($diagnostico->regla_decision_id)
                    <p class="text-xs text-blue-100 mt-1">
                        Diagnóstico inferido: solo se permite ajustar campos clínicos (no síntomas).
                    </p>
                @endif
            </div>

            <a href="{{ route('diagnosticos.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                Volver a la Lista
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-input-error class="mb-2" :messages="$errors->all()" />
            <x-flash-message type="success" />
            <x-flash-message type="warning" />
            <x-flash-message type="error" />

            @if($diagnostico->regla_decision_id)
                <div class="border border-yellow-200 bg-yellow-50 text-yellow-900 rounded-lg p-4 text-sm">
                    Este diagnóstico es inferido. La edición de síntomas está bloqueada para preservar trazabilidad.
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 bg-gray-50 border-b">
                    <h4 class="text-lg font-semibold text-blue-700">Información General</h4>
                </div>

                <form method="POST" action="{{ route('diagnosticos.update', $diagnostico->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-8 text-gray-800">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="tipo_enfermedad" :value="__('Tipo de Enfermedad')" />
                                <x-select id="tipo_enfermedad" name="tipo_enfermedad" class="block mt-1 w-full" required>
                                    <option value="aguda" @selected(old('tipo_enfermedad', $diagnostico->tipo_enfermedad) == 'aguda')>Aguda</option>
                                    <option value="cronica" @selected(old('tipo_enfermedad', $diagnostico->tipo_enfermedad) == 'cronica')>Crónica</option>
                                </x-select>
                            </div>

                            <div>
                                <x-input-label for="estado_id" :value="__('Estado de la enfermedad')" />
                                <x-select id="estado_id" name="estado_id" class="block mt-1 w-full" required>
                                    <option value="">{{__('Elige una opción')}}</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}" @selected(old('estado_id', $diagnostico->estado_id) == $estado->id)>
                                            {{ $estado->estado }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div>
                                <x-input-label for="comienzo_id" :value="__('Comienzo fase crónica')" />
                                <x-select id="comienzo_id" name="comienzo_id" class="block mt-1 w-full" required>
                                    <option value="">{{ __('Elige una opción') }}</option>
                                    @foreach ($comienzos as $comienzo)
                                        <option value="{{ $comienzo->id }}" @selected(old('comienzo_id', $diagnostico->comienzo_id) == $comienzo->id)>
                                            {{ $comienzo->tipo_comienzo }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <p class="text-xs text-gray-500 mt-1">Solo aplica si tipo = crónica.</p>
                            </div>

                            <div>
                                <x-input-label for="escala_karnofsky" :value="__('Escala de Karnofsky')" />
                                <x-select id="escala_karnofsky" name="escala_karnofsky" class="block mt-1 w-full" required>
                                    <option value="">{{ __('Selecciona una opción') }}</option>
                                    @for ($i = 0; $i <= 4; $i++)
                                        <option value="{{ $i }}" @selected(old('escala_karnofsky', $diagnostico->escala_karnofsky) == $i)>{{ $i }}</option>
                                    @endfor
                                </x-select>
                            </div>

                            <div>
                                <x-input-label for="estado_injerto" :value="__('Estado del Injerto')" />
                                <x-select id="estado_injerto" name="estado_injerto" class="block mt-1 w-full" required>
                                    <option value="estable" @selected(old('estado_injerto', $diagnostico->estado_injerto) == 'estable')>Estable</option>
                                    <option value="rechazo" @selected(old('estado_injerto', $diagnostico->estado_injerto) == 'rechazo')>Rechazo</option>
                                </x-select>
                            </div>

                            <div>
                                <x-input-label for="infeccion_id" :value="__('Tipo de infección')" />
                                <x-select id="infeccion_id" name="infeccion_id" class="block mt-1 w-full" required>
                                    <option value="">{{ __('Elige una opción') }}</option>
                                    @foreach ($infeccions as $infeccion)
                                        <option value="{{ $infeccion->id }}" @selected(old('infeccion_id', $diagnostico->infeccion_id) == $infeccion->id)>
                                            {{ $infeccion->nombre }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold text-blue-700 mb-3">Observaciones</h4>

                            <x-input-label for="observaciones" :value="__('Observaciones')" />
                            <textarea id="observaciones" name="observaciones" rows="3"
                                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('observaciones', $diagnostico->observaciones) }}</textarea>
                        </div>

                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold text-blue-700 mb-3">Síntomas asociados</h4>

                            @if($diagnostico->sintomas && $diagnostico->sintomas->count())
                                <div class="overflow-x-auto border rounded">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Síntoma</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha diagnóstico</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Score NIH</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($diagnostico->sintomas as $sintoma)
                                                <tr>
                                                    <td class="px-3 py-2">
                                                        <div class="font-medium">{{ $sintoma->sintoma }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            Órgano: {{ optional($sintoma->organo)->nombre ?? '-' }}
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        {{ optional($sintoma->pivot->fecha_diagnostico)->format('d/m/Y') ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        {{ $sintoma->pivot->score_nih ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">No hay síntomas asociados a este diagnóstico.</p>
                            @endif
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm shadow">
                                Guardar cambios
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Añadir síntomas SOLO si NO es inferido --}}
            @if(!Auth::user()->es_paciente && !$diagnostico->regla_decision_id)
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b">
                        <h4 class="text-lg font-semibold text-blue-700">Nuevos síntomas diagnosticados</h4>
                        <p class="text-xs text-gray-500 mt-1">
                            El listado se agrupa por órgano. Si el catálogo tiene “fase” (aguda/crónica), se filtra por tipo de enfermedad.
                        </p>
                    </div>

                    <div class="p-6">
                        <x-input-error class="mb-4" :messages="$errors->attach->all()" />

                        <form method="POST" action="{{ route('diagnosticos.attachSintoma', [$diagnostico->id]) }}">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <x-input-label for="sintoma_id" :value="__('Síntoma')" />

                                    <select id="sintoma_id" name="sintoma_id" required
                                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">{{ __('Elige un síntoma') }}</option>

                                        @php
                                            $sintomasAgrupados = $sintomas->groupBy(fn($s) => optional($s->organo)->nombre ?? 'Sin órgano');
                                        @endphp

                                        @foreach($sintomasAgrupados as $organoNombre => $items)
                                            <optgroup label="{{ $organoNombre }}">
                                                @foreach($items as $sintoma)
                                                    @php
                                                        // Si existe un campo que marque fase en el catálogo, usarlo.
                                                        // Si no existe, queda vacío y el filtro no rompe nada.
                                                        $fase = $sintoma->fase
                                                            ?? $sintoma->tipo_enfermedad
                                                            ?? null;
                                                    @endphp

                                                    <option
                                                        value="{{ $sintoma->id }}"
                                                        data-fase="{{ $fase ? strtolower((string)$fase) : '' }}"
                                                        @selected(old('sintoma_id') == $sintoma->id)
                                                    >
                                                        {{ $sintoma->sintoma }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-1">
                                    <x-input-label for="fecha_diagnostico" :value="__('Fecha de diagnóstico')" />
                                    <x-text-input id="fecha_diagnostico" class="block mt-1 w-full"
                                                  type="date"
                                                  name="fecha_diagnostico"
                                                  :value="old('fecha_diagnostico')"
                                                  required/>
                                </div>

                                <div class="md:col-span-1">
                                    <x-input-label for="score_nih" :value="__('Score NIH')" />
                                    <x-select id="score_nih" name="score_nih" class="block mt-1 w-full" required>
                                        <option value="">{{__('Marcar el valor')}}</option>
                                        @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" @selected(old('score_nih') == $i)>{{ $i }}</option>
                                        @endfor
                                    </x-select>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-3">
                                <a href="{{ route('diagnosticos.index') }}"
                                   class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                                    Cancelar
                                </a>

                                <button type="submit"
                                        class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm shadow">
                                    Guardar síntoma
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    (function () {
                        const tipo = document.getElementById('tipo_enfermedad');
                        const select = document.getElementById('sintoma_id');

                        if (!tipo || !select) return;

                        function filtrar() {
                            const fase = (tipo.value || '').toLowerCase();
                            const options = Array.from(select.querySelectorAll('option[data-fase]'));

                            // Si no hay data-fase en el catálogo, no filtrar (no romper nada).
                            const hayFase = options.some(o => (o.dataset.fase || '').length > 0);
                            if (!hayFase) return;

                            options.forEach(opt => {
                                const f = (opt.dataset.fase || '').toLowerCase();

                                // Regla simple:
                                // - Si el síntoma no declara fase -> se deja visible.
                                // - Si declara fase -> solo visible si coincide con el tipo actual.
                                const visible = !f || f === fase;

                                opt.hidden = !visible;

                                // Si el usuario tenía seleccionado algo que se oculta, se resetea.
                                if (opt.selected && !visible) {
                                    opt.selected = false;
                                    select.value = '';
                                }
                            });
                        }

                        tipo.addEventListener('change', filtrar);
                        filtrar();
                    })();
                </script>
            @endif

        </div>
    </div>
</x-medico-layout>
