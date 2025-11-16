@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Tipo de trasplante --}}
    <div>
        <x-input-label for="tipo_trasplante" value="Tipo de trasplante" />

        <select name="tipo_trasplante" id="tipo_trasplante"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">
            <option value="">Seleccione...</option>

            @foreach(['alogénico emparentado','alogénico no emparentado','autólogo','singénico'] as $tipo)
                <option value="{{ $tipo }}"
                    @selected(old('tipo_trasplante', $trasplante->tipo_trasplante ?? '') == $tipo)>
                    {{ ucfirst($tipo) }}
                </option>
            @endforeach
        </select>

        <x-input-error :messages="$errors->get('tipo_trasplante')" class="mt-2" />
    </div>

    {{-- Fecha --}}
    <div>
        <x-input-label for="fecha_trasplante" value="Fecha del trasplante" />
        <x-text-input type="date" name="fecha_trasplante" id="fecha_trasplante"
            class="w-full mt-1"
            :value="old('fecha_trasplante', isset($trasplante) ? $trasplante->fecha_trasplante->format('Y-m-d') : '')" />

        <x-input-error :messages="$errors->get('fecha_trasplante')" class="mt-2" />
    </div>

    {{-- Origen del injerto --}}
    <div>
        <x-input-label for="origen_trasplante" value="Origen del injerto" />

        <select name="origen_trasplante" id="origen_trasplante"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">

            <option value="">Seleccione...</option>

            @foreach(['médula ósea','sangre periférica'] as $origen)
                <option value="{{ $origen }}"
                    @selected(old('origen_trasplante', $trasplante->origen_trasplante ?? '') == $origen)>
                    {{ ucfirst($origen) }}
                </option>
            @endforeach

        </select>

        <x-input-error :messages="$errors->get('origen_trasplante')" class="mt-2" />
    </div>

    {{-- Compatibilidad HLA --}}
    <div>
        <x-input-label for="identidad_hla" value="Compatibilidad HLA" />

        <select name="identidad_hla" id="identidad_hla"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">
            <option value="">Seleccione...</option>

            @foreach(['idéntico','disparidad clase I','disparidad clase II'] as $hla)
                <option value="{{ $hla }}"
                    @selected(old('identidad_hla', $trasplante->identidad_hla ?? '') == $hla)>
                    {{ ucfirst($hla) }}
                </option>
            @endforeach
        </select>

        <x-input-error :messages="$errors->get('identidad_hla')" class="mt-2" />
    </div>

    {{-- Tipo de acondicionamiento --}}
    <div>
        <x-input-label for="tipo_acondicionamiento" value="Tipo de acondicionamiento" />

        <select name="tipo_acondicionamiento" id="tipo_acondicionamiento"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">

            <option value="">Seleccione...</option>

            @foreach(['intensidad reducida','mieloablativo'] as $tc)
                <option value="{{ $tc }}"
                    @selected(old('tipo_acondicionamiento', $trasplante->tipo_acondicionamiento ?? '') == $tc)>
                    {{ ucfirst($tc) }}
                </option>
            @endforeach
        </select>

        <x-input-error :messages="$errors->get('tipo_acondicionamiento')" class="mt-2" />
    </div>

    {{-- Serología Donante --}}
    <div>
        <x-input-label for="seropositividad_donante" value="Serología Donante" />

        <select name="seropositividad_donante" id="seropositividad_donante"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">
            <option value="">Seleccione...</option>
            <option value="+" @selected(old('seropositividad_donante', $trasplante->seropositividad_donante ?? '') == '+')>+</option>
            <option value="-" @selected(old('seropositividad_donante', $trasplante->seropositividad_donante ?? '') == '-')>-</option>
        </select>

        <x-input-error :messages="$errors->get('seropositividad_donante')" class="mt-2" />
    </div>

    {{-- Serología Receptor --}}
    <div>
        <x-input-label for="seropositividad_receptor" value="Serología Receptor" />

        <select name="seropositividad_receptor" id="seropositividad_receptor"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">
            <option value="">Seleccione...</option>
            <option value="+" @selected(old('seropositividad_receptor', $trasplante->seropositividad_receptor ?? '') == '+')>+</option>
            <option value="-" @selected(old('seropositividad_receptor', $trasplante->seropositividad_receptor ?? '') == '-')>-</option>
        </select>

        <x-input-error :messages="$errors->get('seropositividad_receptor')" class="mt-2" />
    </div>

    {{-- Campo PACIENTE --}}
    <div class="md:col-span-2">
        <x-input-label for="paciente_id" value="Paciente" />

        <select name="paciente_id" id="paciente_id"
                class="w-full border-gray-300 rounded-md shadow-sm mt-1">

            <option value="">Seleccione paciente...</option>

            @foreach($pacientes as $p)
                <option value="{{ $p->id }}"
                    @selected(old('paciente_id', $trasplante->paciente_id ?? '') == $p->id)>
                    {{ $p->nombre }} ({{ $p->nuhsa }})
                </option>
            @endforeach

        </select>

        <x-input-error :messages="$errors->get('paciente_id')" class="mt-2" />
    </div>

</div>

{{-- Botones --}}
<div class="flex justify-end mt-8">
    <a href="{{ route('trasplantes.index') }}"
       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
        Cancelar
    </a>

    <button class="ml-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Guardar
    </button>
</div>
