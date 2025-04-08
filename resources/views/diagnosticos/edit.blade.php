<x-app-layout>
    <x-slot name="header">
        <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
            <h3 class="text-lg font-semibold">Editar Diagnóstico</h3>
            <a href="{{ route('diagnosticos.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Volver a la Lista
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Información General -->
            <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Información General</h4>

                <x-input-error class="mb-4" :messages="$errors->all()" />

                <form method="POST" action="{{ route('diagnosticos.update', $diagnostico->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tipo_enfermedad" :value="__('Tipo de Enfermedad')" />
                            <x-select id="tipo_enfermedad" name="tipo_enfermedad" required>
                                <option value="aguda" @selected(old('tipo_enfermedad', $diagnostico->tipo_enfermedad) == 'aguda')>Aguda</option>
                                <option value="cronica" @selected(old('tipo_enfermedad', $diagnostico->tipo_enfermedad) == 'cronica')>Crónica</option>
                            </x-select>
                        </div>

                        <div>
                            <x-input-label for="estado_id" :value="__('Estado de enfermedad')" />
                            <x-select id="estado_id" name="estado_id" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}" @selected(old('estado_id', $diagnostico->estado_id) == $estado->id)>{{ $estado->estado }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div>
                            <x-input-label for="comienzo_id" :value="__('Comienzo fase crónica')" />
                            <x-select id="comienzo_id" name="comienzo_id" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                @foreach ($comienzos as $comienzo)
                                    <option value="{{ $comienzo->id }}" @selected(old('comienzo_id', $diagnostico->comienzo_id) == $comienzo->id)>{{ $comienzo->tipo_comienzo }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div>
                            <x-input-label for="escala_karnofsky" :value="__('Escala de Karnofsky (0-4)')" />
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
                            <x-select id="infeccion_id" name="infeccion_id" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                @foreach ($infeccions as $infeccion)
                                    <option value="{{ $infeccion->id }}" @selected(old('infeccion_id', $diagnostico->infeccion_id) == $infeccion->id)>{{ $infeccion->nombre }}</option>
                                @endforeach
                            </x-select>
                        </div>
                    </div>

                    <!-- Fechas Clínicas -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Fechas Clínicas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(['f_trasplante' => 'Fecha de Trasplante', 'f_electromiografia' => 'Fecha de Electromiografía', 'f_eval_injerto' => 'Fecha de Evaluación del Injerto', 'f_medulograma' => 'Fecha de Medulograma', 'f_espirometria' => 'Fecha de Espirometría', 'f_esplenectomia' => 'Fecha de Esplenectomía'] as $field => $label)
                                <div>
                                    <x-input-label :for="$field" :value="__($label)" />
                                    <x-text-input :id="$field" :name="$field" type="datetime-local"
                                        class="block mt-1 w-full" :value="old($field, optional($diagnostico->$field)->format('Y-m-d\TH:i'))" />
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <x-input-label for="dias_desde_trasplante" :value="__('Días desde Trasplante')" />
                            <x-text-input id="dias_desde_trasplante" name="dias_desde_trasplante" type="number"
                                class="block mt-1 w-full bg-gray-100" :value="$diagnostico->dias_desde_trasplante ?? ''" readonly />
                        </div>
                    </div>

                    <!-- Evaluaciones y Observaciones -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Evaluaciones y Observaciones</h4>

                        <div class="mt-4">
                            <x-input-label for="hipoalbuminemia" :value="__('Hipoalbuminemia')" />
                            <x-select id="hipoalbuminemia" name="hipoalbuminemia" class="block mt-1 w-full" required>
                                <option value="si" @selected(old('hipoalbuminemia', $diagnostico->hipoalbuminemia) == 'si')>Sí</option>
                                <option value="no" @selected(old('hipoalbuminemia', $diagnostico->hipoalbuminemia) == 'no')>No</option>
                            </x-select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="observaciones" :value="__('Observaciones')" />
                            <textarea id="observaciones" name="observaciones" rows="3"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('observaciones', $diagnostico->observaciones) }}</textarea>
                        </div>
                    </div>

                    <!-- Síntomas Asociados -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Síntomas Asociados</h4>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Síntoma</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de diagnóstico</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score NIH</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($diagnostico->sintomas as $sintoma)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $sintoma->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $sintoma->pivot->fecha_diagnostico->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $sintoma->pivot->score_nih }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-danger-button type="button">
                            <a href="{{ route('diagnosticos.index') }}">{{ __('Cancelar') }}</a>
                        </x-danger-button>
                        <x-primary-button class="ml-4">
                            {{ __('Guardar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
