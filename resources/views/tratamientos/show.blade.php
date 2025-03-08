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
                    <a href="#" class="text-gray-500" aria-current="page">Ver tratamiento</a>
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
                    <!-- Nombre del Tratamiento -->
                    <div class="mt-4">
                        <x-input-label for="tratamiento" :value="__('Nombre del Tratamiento')"/>
                        <x-text-input id="tratamiento" class="block mt-1 w-full" type="text" disabled :value="$tratamiento->tratamiento"/>
                    </div>

                    <!-- Fecha de Asignación -->
                    <div class="mt-4">
                        <x-input-label for="fecha_asignacion" :value="__('Fecha de Asignación')"/>
                        <x-text-input id="fecha_asignacion" class="block mt-1 w-full" type="date" disabled :value="$tratamiento->fecha_asignacion->format('Y-m-d')"/>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-4">
                        <x-input-label for="descripcion" :value="__('Descripción')"/>
                        <textarea id="descripcion" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" disabled>{{ $tratamiento->descripcion }}</textarea>
                    </div>

                    <!-- Duración del Tratamiento -->
                    <div class="mt-4">
                        <x-input-label for="duracion_trat" :value="__('Duración del Tratamiento (días)')"/>
                        <x-text-input id="duracion_trat" class="block mt-1 w-full" type="number" disabled :value="$tratamiento->duracion_trat"/>
                    </div>

                    <!-- Médico -->
                    <div class="mt-4">
                        <x-input-label for="medico_id" :value="__('Médico')"/>
                        <x-text-input class="block mt-1 w-full" type="text" disabled :value="$tratamiento->medico->user->name"/>
                    </div>

                    <!-- Paciente -->
                    <div class="mt-4">
                        <x-input-label for="paciente_id" :value="__('Paciente')"/>
                        <x-text-input class="block mt-1 w-full" type="text" disabled :value="$tratamiento->paciente->user->name"/>
                    </div>

                    <!-- Botón de Volver -->
                    <div class="flex items-center justify-end mt-4">
                        <x-danger-button type="button">
                            <a href="{{ route('tratamientos.index') }}">{{ __('Volver') }}</a>
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Líneas de Tratamiento -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Líneas de Tratamiento
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Medicamento</th>
                            <th class="py-3 px-6 text-left">Inicio</th>
                            <th class="py-3 px-6 text-left">Fin</th>
                            <th class="py-3 px-6 text-left">Tomas/día</th>
                            <th class="py-3 px-6 text-left">Observaciones</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($tratamiento->lineasTratamiento as $linea)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <!-- Medicamento -->
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->nombre }}</span>
                                    </div>
                                </td>

                                <!-- Fecha de Inicio -->
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->fecha_ini_linea->format('d/m/Y') }}</span>
                                    </div>
                                </td>

                                <!-- Fecha de Fin -->
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->fecha_fin_linea->format('d/m/Y') }}</span>
                                    </div>
                                </td>

                                <!-- Tomas al Día -->
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->tomas }}</span>
                                    </div>
                                </td>

                                <!-- Observaciones -->
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $linea->pivot->observaciones }}</span>
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
</x-app-layout>