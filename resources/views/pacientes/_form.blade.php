@csrf

<x-input-error class="mb-4" :messages="$errors->all()" />

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Solo mostrar campo nombre si es CREATE --}}
    @if(!isset($paciente))
        <div>
            <x-input-label for="name" value="Nombre y apellidos" />
            <x-text-input id="name" name="name" type="text" class="w-full mt-1"
                :value="old('name')" required />
        </div>
    @endif

    <div>
        <x-input-label for="nuhsa" value="NUHSA" />
        <x-text-input id="nuhsa" name="nuhsa" type="text" class="w-full mt-1"
            :value="old('nuhsa', $paciente->nuhsa ?? '')" required />
    </div>

    <div>
        <x-input-label for="fecha_nacimiento" value="Fecha de nacimiento" />
        <x-text-input id="fecha_nacimiento" name="fecha_nacimiento" type="date"
            class="w-full mt-1"
            :value="old('fecha_nacimiento', isset($paciente) ? $paciente->fecha_nacimiento->format('Y-m-d') : '')"
            required />
    </div>

    <div>
        <x-input-label for="peso" value="Peso (kg)" />
        <x-text-input id="peso" name="peso" type="number" step="0.1"
            class="w-full mt-1"
            :value="old('peso', $paciente->peso ?? '')" required />
    </div>

    <div>
        <x-input-label for="altura" value="Altura (cm)" />
        <x-text-input id="altura" name="altura" type="number" step="1"
            class="w-full mt-1"
            :value="old('altura', $paciente->altura ?? '')" required />
    </div>

    <div>
        <x-input-label for="sexo" value="Sexo" />
        <x-select id="sexo" name="sexo" class="w-full" required>
            <option value="">Elige una opción</option>
            <option value="M" @selected(old('sexo', $paciente->sexo ?? '') == 'M')>Masculino</option>
            <option value="F" @selected(old('sexo', $paciente->sexo ?? '') == 'F')>Femenino</option>
        </x-select>
    </div>

</div>

<div class="flex justify-end mt-6 gap-4">
    <a href="{{ route('pacientes.index') }}"
       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
        Cancelar
    </a>

    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Guardar
    </button>
</div>
