<x-medico-layout>

    {{-- BREADCRUMB --}}
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none inline-flex items-center p-0">
                <li>
                    <a href="{{ route('tratamientos.index') }}" class="hover:text-blue-700">
                        Tratamientos
                    </a>
                </li>

                <li class="mx-2 text-gray-500">›</li>

                <li class="text-gray-500">Ver tratamiento</li>
            </ol>
        </nav>
    </x-slot>


    {{-- FICHA PRINCIPAL --}}
    <div class="py-6 px-4">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA --}}
                <div class="bg-blue-600 text-white p-5">
                    <h3 class="text-lg font-semibold tracking-wide">
                        Información del tratamiento
                    </h3>
                </div>

                {{-- CAMPOS (inputs deshabilitados) --}}
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre --}}
                    <div>
                        <x-input-label for="tratamiento" value="Nombre del tratamiento" />
                        <x-text-input id="tratamiento"
                                      type="text"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->tratamiento"/>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <x-input-label for="fecha_asignacion" value="Fecha de asignación" />
                        <x-text-input id="fecha_asignacion"
                                      type="date"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->fecha_asignacion->format('Y-m-d')"/>
                    </div>

                    {{-- Paciente --}}
                    <div>
                        <x-input-label for="paciente" value="Paciente" />
                        <x-text-input id="paciente"
                                      type="text"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->paciente->user->name . ' (' . $tratamiento->paciente->nuhsa . ')'" />
                    </div>

                    {{-- Duración total --}}
                    <div>
                        <x-input-label for="duracion_total" value="Duración total (días)" />
                        <x-text-input id="duracion_total"
                                      type="text"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->duracion_total . ' días'"/>
                    </div>

                    {{-- Descripción (span visual con apariencia de texto clínico) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="descripcion" value="Descripción" />
                        <textarea id="descripcion"
                                  class="w-full mt-1 rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-700"
                                  rows="5"
                                  disabled>{{ $tratamiento->descripcion }}</textarea>
                    </div>

                </div>

                {{-- BOTÓN VOLVER --}}
                <div class="px-6 py-4 bg-gray-50 flex justify-end border-t border-gray-200">
                    <a href="{{ route('tratamientos.index') }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
                        Volver
                    </a>
                </div>

            </div>

        </div>
    </div>


    {{-- LÍNEAS DE TRATAMIENTO --}}
    <div class="py-6 px-4">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white shadow-md rounded-lg border border-gray-200">

                {{-- CABECERA --}}
                <div class="bg-blue-600 text-white p-5">
                    <h3 class="text-lg font-semibold tracking-wide">
                        Líneas de tratamiento
                    </h3>
                </div>

                {{-- TABLA --}}
                <div class="p-6">

                    <table class="min-w-full border border-gray-200 rounded-md">
                        <thead class="bg-blue-50 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th class="py-2 px-3 border-b text-left">Medicamento</th>
                                <th class="py-2 px-3 border-b text-left">Inicio</th>
                                <th class="py-2 px-3 border-b text-left">Fin</th>
                                <th class="py-2 px-3 border-b text-left">Tomas/día</th>
                                <th class="py-2 px-3 border-b text-left">Observaciones</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700 text-sm">
                            @foreach($tratamiento->lineasTratamiento as $linea)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="py-3 px-3 border-b">
                                        {{ $linea->nombre }}
                                    </td>

                                    <td class="py-3 px-3 border-b">
                                        {{ optional($linea->pivot->fecha_ini_linea)->format('d/m/Y') }}
                                    </td>

                                    <td class="py-3 px-3 border-b">
                                        {{ optional($linea->pivot->fecha_fin_linea)->format('d/m/Y') }}
                                    </td>

                                    <td class="py-3 px-3 border-b">
                                        {{ $linea->pivot->tomas }}
                                    </td>

                                    <td class="py-3 px-3 border-b">
                                        {{ $linea->pivot->observaciones ?: '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

</x-medico-layout>
