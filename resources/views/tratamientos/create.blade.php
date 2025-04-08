<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('tratamientos.index') }}">Tratamientos</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">Crear nuevo registro</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Información del tratamiento
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Errores de validación en servidor -->
                    <x-input-error class="mb-4" :messages="$errors->all()" />
                    <form method="POST" action="{{ route('tratamientos.store') }}">
                        @csrf
                        <div class="mt-4">
                            <x-input-label for="tratamiento" :value="__('Tratamiento')" />
                            <x-text-input id="tratamiento" class="block mt-1 w-full"
                                          type="text"
                                          name="tratamiento"
                                          :value="old('tratamiento')"
                                          required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fecha_asignacion" :value="__('Fecha de asignación')" />
                            <x-text-input id="fecha_asignacion" class="block mt-1 w-full"
                                          type="date"
                                          name="fecha_asignacion"
                                          :value="old('fecha_asignacion')"
                                          required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="descripcion" :value="__('Descripción')" />
                            <x-textarea id="descripcion" class="block mt-1 w-full"
                                         name="descripcion"
                                         required>{{ old('descripcion') }}</x-textarea>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="duracion_trat" :value="__('Duración del tratamiento (días)')" />
                            <x-text-input id="duracion_trat" class="block mt-1 w-full"
                                          type="number"
                                          name="duracion_trat"
                                          :value="old('duracion_trat')"
                                          required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="paciente_id" :value="__('Paciente')" />
                            @isset($paciente)
                                <x-text-input id="paciente_id" class="block mt-1 w-full"
                                              type="hidden"
                                              name="paciente_id"
                                              :value="$paciente->id"
                                              required />
                                <x-text-input class="block mt-1 w-full"
                                              type="text"
                                              disabled
                                              value="{{$paciente->user->name}} ({{$paciente->nuhsa}})"
                                />
                            @else
                                <x-select id="paciente_id" name="paciente_id" required>
                                    <option value="">{{__('Elige un paciente')}}</option>
                                    @foreach ($pacientes as $paciente)
                                        <option value="{{$paciente->id}}" @if (old('paciente_id') == $paciente->id) selected @endif>{{$paciente->user->name}} ({{$paciente->nuhsa}})</option>
                                    @endforeach
                                </x-select>
                            @endisset
                        </div>

                        <div class="mt-4">
                            <x-input-label for="medico_id" :value="__('Médico')" />
                            @isset($medico)
                                <x-text-input id="medico_id" class="block mt-1 w-full"
                                              type="hidden"
                                              name="medico_id"
                                              :value="$medico->id"
                                              required />
                                <x-text-input class="block mt-1 w-full"
                                              type="text"
                                              disabled
                                              value="{{$medico->user->name}} ({{$medico->especialidad->nombre}})"
                                />
                            @else
                                <x-select id="medico_id" name="medico_id" required>
                                    <option value="">{{__('Elige un médico')}}</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{$medico->id}}" @if (old('medico_id') == $medico->id) selected @endif>{{$medico->user->name}} ({{$medico->especialidad->nombre}})</option>
                                    @endforeach
                                </x-select>
                            @endisset
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-button type="button">
                                <a href="{{ route('tratamientos.index') }}">
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