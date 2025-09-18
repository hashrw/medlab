<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('tratamientos.index') }}">Tratamientos</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">Editar tratamiento</a>
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
                    <x-input-error class="mb-4" :messages="$errors->all()"/>
                    <form method="POST" action="{{ route('tratamientos.update', $tratamiento->id) }}">
                        @csrf
                        @method('put')

                        <!-- Campos del Tratamiento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tratamiento (Nombre) -->
                            <div class="mb-4">
                                <x-input-label for="tratamiento" :value="__('Nombre del Tratamiento')"/>
                                <x-text-input id="tratamiento" name="tratamiento" value="{{ $tratamiento->tratamiento }}" class="block mt-1 w-full"/>
                            </div>

                            <!-- Fecha de Asignación -->
                            <div class="mb-4">
                                <x-input-label for="fecha_asignacion" :value="__('Fecha de Asignación')"/>
                                <x-text-input id="fecha_asignacion" name="fecha_asignacion" type="date" value="{{ $tratamiento->fecha_asignacion->format('Y-m-d') }}" class="block mt-1 w-full"/>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-4 col-span-2">
                                <x-input-label for="descripcion" :value="__('Descripción')"/>
                                <textarea id="descripcion" name="descripcion" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ $tratamiento->descripcion }}</textarea>
                            </div>

                           {{--   <!-- Duración del Tratamiento -->
                            <div class="mb-4">
                                <x-input-label for="duracion_trat" :value="__('Duración del Tratamiento (días)')"/>
                                <x-text-input id="duracion_trat" name="duracion_trat" type="number" value="{{ $tratamiento->duracion_trat }}" class="block mt-1 w-full"/>
                            </div>--}}

                            <!-- Médico -->
                            <div class="mb-4">
                                <x-input-label for="medico_id" :value="__('Médico')"/>
                                <x-select id="medico_id" name="medico_id" class="block mt-1 w-full">
                                    @foreach($medicos as $medico)
                                        <option value="{{ $medico->id }}" {{ $tratamiento->medico_id == $medico->id ? 'selected' : '' }}>
                                            {{ $medico->user->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <!-- Paciente -->
                            <div class="mb-4">
                                <x-input-label for="paciente_id" :value="__('Paciente')"/>
                                <x-select id="paciente_id" name="paciente_id" class="block mt-1 w-full">
                                    @foreach($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}" {{ $tratamiento->paciente_id == $paciente->id ? 'selected' : '' }}>
                                            {{ $paciente->user->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>

                        <!-- Botón de Guardar -->
                        <div class="flex items-center justify-end mt-4">
                            <x-danger-button type="button">
                                <a href="{{ route('tratamientos.index') }}">{{ __('Cancelar') }}</a>
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

    <!-- Líneas de Tratamiento Existentes -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Líneas de Tratamiento Actuales
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Medicamento</th>
                            <th class="py-3 px-6 text-left">Fecha de inicio</th>
                            <th class="py-3 px-6 text-left">Fecha de fin</th>
                            <th class="py-3 px-6 text-left">Tomas/día</th>
                            <th class="py-3 px-6 text-left">Comentarios</th>
                            <th class="py-3 px-6 text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($tratamiento->lineasTratamiento as $linea)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->nombre }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->fecha_ini_linea->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->fecha_fin_linea->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->tomas }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->observaciones }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <!-- Botón para eliminar línea de tratamiento -->
                                        <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <form id="detach-form-{{ $tratamiento->id }}-{{ $linea->id }}" method="POST"
                                                  action="{{ route('tratamientos.detachLinea', [$tratamiento->id, $linea->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <a class="cursor-pointer"
                                                   onclick="getElementById('detach-form-{{ $tratamiento->id }}-{{ $linea->id }}').submit();">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Añadir Nueva Línea de Tratamiento -->
    @if(!Auth::user()->es_paciente)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                        Añadir Línea de Tratamiento
                    </div>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Errores de validación en servidor -->
                        <x-input-error class="mb-4" :messages="$errors->attach->all()"/>
                        <form method="POST" action="{{ route('tratamientos.attachLinea', $tratamiento->id) }}">
                            @csrf

                            <!-- Medicamento -->
                            <div class="mt-4">
                                <x-input-label for="medicamento_id" :value="__('Medicamento')"/>
                                <x-select id="medicamento_id" name="medicamento_id" class="block mt-1 w-full" required>
                                    <option value="">{{ __('Elige un medicamento') }}</option>
                                    @foreach($medicamentos as $medicamento)
                                        <option value="{{ $medicamento->id }}">{{ $medicamento->nombre }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <!-- Fecha de Inicio -->
                            <div class="mt-4">
                                <x-input-label for="fecha_ini_linea" :value="__('Fecha de Inicio')"/>
                                <x-text-input id="fecha_ini_linea" name="fecha_ini_linea" type="date" class="block mt-1 w-full" required/>
                            </div>

                            <!-- Fecha de Fin -->
                            <div class="mt-4">
                                <x-input-label for="fecha_fin_linea" :value="__('Fecha de Fin')"/>
                                <x-text-input id="fecha_fin_linea" name="fecha_fin_linea" type="date" class="block mt-1 w-full" required/>
                            </div>

                            <!-- Tomas -->
                            <div class="mt-4">
                                <x-input-label for="tomas" :value="__('Tomas al Día')"/>
                                <x-text-input id="tomas" name="tomas" type="number" class="block mt-1 w-full" required/>
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-4">
                                <x-input-label for="observaciones" :value="__('Observaciones')"/>
                                <textarea id="observaciones" name="observaciones" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>

                            <!-- Botón de Guardar -->
                            <div class="flex items-center justify-end mt-4">
                                <x-danger-button type="button">
                                    <a href="{{ route('tratamientos.index') }}">{{ __('Cancelar') }}</a>
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
    @endif
</x-medico-layout>
