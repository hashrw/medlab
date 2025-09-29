{{-- resources/views/diagnosticos/partials/form-fields.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="tipo_enfermedad" :value="__('Tipo de Enfermedad')" />
        <x-select id="tipo_enfermedad" name="tipo_enfermedad" required>
            <option value="">{{ __('Elige un tipo') }}</option>
            <option value="aguda" @if (old('tipo_enfermedad') == 'aguda') selected @endif>Aguda</option>
            <option value="cronica" @if (old('tipo_enfermedad') == 'cronica') selected @endif>Crónica</option>
        </x-select>
    </div>

    <div>
        <x-input-label for="estado_id" :value="__('Estado de la enfermedad')" />
        <x-select id="estado_id" name="estado_id" required>
            <option value="">{{__('Elige una opción')}}</option>
            @foreach ($estados as $estado)
                <option value="{{$estado->id}}" @if (old('estado_id') == $estado->id) selected @endif>
                    {{$estado->estado}}
                </option>
            @endforeach
        </x-select>
    </div>

    <div>
        <x-input-label for="comienzo_id" :value="__('Tipo de comienzo fase crónica')" />
        <x-select id="comienzo_id" name="comienzo_id" required>
            <option value="">{{ __('Elige una opción') }}</option>
            @foreach ($comienzos as $comienzo)
                <option value="{{ $comienzo->id }}" @if (old('comienzo_id') == $comienzo->id) selected @endif>
                    {{ $comienzo->tipo_comienzo }}
                </option>
            @endforeach
        </x-select>
    </div>

    <div>
        <x-input-label for="escala_karnofsky" :value="__('Escala de Karnofsky (0-4)')" />
        <x-select id="escala_karnofsky" name="escala_karnofsky" class="block mt-1 w-full" required>
            <option value="">{{ __('Selecciona una opción') }}</option>
            @for ($i = 0; $i <= 4; $i++)
                <option value="{{ $i }}" @if (old('escala_karnofsky') == $i) selected @endif>
                    {{ $i }}
                </option>
            @endfor
        </x-select>
    </div>

    <div>
        <x-input-label for="estado_injerto" :value="__('Estado del Injerto')" />
        <x-select id="estado_injerto" name="estado_injerto" required>
            <option value="">{{ __('Elige un estado') }}</option>
            <option value="estable" @if (old('estado_injerto') == 'estable') selected @endif>Estable</option>
            <option value="rechazo" @if (old('estado_injerto') == 'rechazo') selected @endif>Rechazo</option>
        </x-select>
    </div>

    <div>
        <x-input-label for="infeccion_id" :value="__('Tipo de infección')" />
        <x-select id="infeccion_id" name="infeccion_id" required>
            <option value="">{{ __('Elige una opción') }}</option>
            @foreach ($infeccions as $infeccion)
                <option value="{{ $infeccion->id }}" @if (old('infeccion_id') == $infeccion->id) selected @endif>
                    {{ $infeccion->nombre }}
                </option>
            @endforeach
        </x-select>
    </div>
</div>

{{-- Fechas Clínicas --}}
<div class="mt-8">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Fechas Clínicas</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input-label for="f_trasplante" :value="__('Fecha de Trasplante')" />
        <x-text-input id="f_trasplante" name="f_trasplante" type="date" class="block mt-1 w-full"
            :value="old('f_trasplante')" />

        <x-input-label for="f_electromiografia" :value="__('Fecha de Electromiografía')" />
        <x-text-input id="f_electromiografia" name="f_electromiografia" type="datetime-local" class="block mt-1 w-full"
            :value="old('f_electromiografia')" />

        <x-input-label for="f_eval_injerto" :value="__('Fecha de Evaluación del Injerto')" />
        <x-text-input id="f_eval_injerto" name="f_eval_injerto" type="datetime-local" class="block mt-1 w-full"
            :value="old('f_eval_injerto')" />

        <x-input-label for="f_medulograma" :value="__('Fecha de Medulograma')" />
        <x-text-input id="f_medulograma" name="f_medulograma" type="datetime-local" class="block mt-1 w-full"
            :value="old('f_medulograma')" />

        <x-input-label for="f_espirometria" :value="__('Fecha de Espirometría')" />
        <x-text-input id="f_espirometria" name="f_espirometria" type="datetime-local" class="block mt-1 w-full"
            :value="old('f_espirometria')" />

        <x-input-label for="f_esplenectomia" :value="__('Fecha de Esplenectomía')" />
        <x-text-input id="f_esplenectomia" name="f_esplenectomia" type="datetime-local" class="block mt-1 w-full"
            :value="old('f_esplenectomia')" />
    </div>
</div>

{{-- Evaluaciones y Observaciones --}}
<div class="mt-8">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Evaluaciones y Observaciones</h4>

    <div class="mt-4">
        <x-input-label for="hipoalbuminemia" :value="__('Hipoalbuminemia')" />
        <x-select id="hipoalbuminemia" name="hipoalbuminemia" required>
            <option value="">{{ __('Elige una opción') }}</option>
            <option value="si" @if (old('hipoalbuminemia') == 'si') selected @endif>Sí</option>
            <option value="no" @if (old('hipoalbuminemia') == 'no') selected @endif>No</option>
        </x-select>
    </div>

    <div class="mt-4">
        <x-input-label for="observaciones" :value="__('Observaciones')" />
        <textarea id="observaciones" name="observaciones" rows="3"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('observaciones') }}</textarea>
    </div>
</div>