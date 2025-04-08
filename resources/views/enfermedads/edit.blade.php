<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('enfermedads.index') }}">Enfermedades</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">Editar registro</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Información de la enfermedad
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Errores de validación en servidor -->
                    <x-input-error class="mb-4" :messages="$errors->all()"/>
                    <form method="POST" action="{{ route('enfermedads.update', $enfermedad->id) }}">
                        @csrf
                        @method('put')
                        <div class="mt-4">
                            <x-input-label for="tipo_trasplante" :value="__('Tipo de trasplante')"/>
                            <x-text-input id="tipo_trasplante" class="block mt-1 w-full"
                                          type="text"
                                          name="tipo_trasplante"
                                          :value="$enfermedad->tipo_trasplante"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="nombre_enfermedad" :value="__('Nombre de la enfermedad')"/>
                            <x-text-input id="nombre_enfermedad" class="block mt-1 w-full"
                                          type="text"
                                          name="nombre_enfermedad"
                                          :value="$enfermedad->nombre_enfermedad"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fecha_trasplante" :value="__('Fecha de trasplante')"/>
                            <x-text-input id="fecha_trasplante" class="block mt-1 w-full"
                                          type="date"
                                          name="fecha_trasplante"
                                          :value="$enfermedad->fecha_trasplante"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="origen_trasplante" :value="__('Origen del trasplante')"/>
                            <x-text-input id="origen_trasplante" class="block mt-1 w-full"
                                          type="text"
                                          name="origen_trasplante"
                                          :value="$enfermedad->origen_trasplante"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="identidad_hla" :value="__('Identidad HLA')"/>
                            <x-text-input id="identidad_hla" class="block mt-1 w-full"
                                          type="text"
                                          name="identidad_hla"
                                          :value="$enfermedad->identidad_hla"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tipo_acondicionamiento" :value="__('Tipo de acondicionamiento')"/>
                            <x-text-input id="tipo_acondicionamiento" class="block mt-1 w-full"
                                          type="text"
                                          name="tipo_acondicionamiento"
                                          :value="$enfermedad->tipo_acondicionamiento"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="seropositividad_donante" :value="__('Seropositividad del donante')"/>
                            <x-text-input id="seropositividad_donante" class="block mt-1 w-full"
                                          type="text"
                                          name="seropositividad_donante"
                                          :value="$enfermedad->seropositividad_donante"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="seropositividad_receptor" :value="__('Seropositividad del receptor')"/>
                            <x-text-input id="seropositividad_receptor" class="block mt-1 w-full"
                                          type="text"
                                          name="seropositividad_receptor"
                                          :value="$enfermedad->seropositividad_receptor"
                                          required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="paciente_id" :value="__('Paciente')"/>

                            @isset($paciente)
                                <x-text-input id="paciente_id" class="block mt-1 w-full"
                                              type="hidden"
                                              name="paciente_id"
                                              :value="$paciente->id"
                                              required/>
                                <x-text-input class="block mt-1 w-full"
                                              type="text"
                                              disabled
                                              value="{{$paciente->user->name}} ({{$paciente->nuhsa}})"
                                />
                            @else
                                <x-select id="paciente_id" name="paciente_id" required>
                                    <option value="">{{__('Elige un paciente')}}</option>
                                    @foreach ($pacientes as $paciente)
                                        <option value="{{$paciente->id}}"
                                                @if ($enfermedad->paciente_id == $paciente->id) selected @endif>{{$paciente->user->name}}
                                            ({{$paciente->nuhsa}})
                                        </option>
                                    @endforeach
                                </x-select>
                            @endisset
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-button type="button">
                                <a href={{route('enfermedads.index')}}>
                                    {{ __('Cancelar') }}
                                </a>
                            </x-danger-button>
                            <x-primary-button class="ml-4">
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>