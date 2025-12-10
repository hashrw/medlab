<x-medico-layout>
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

    <div class="py-6 px-4">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                <div class="bg-blue-600 text-white p-5">
                    <h3 class="text-lg font-semibold tracking-wide">
                        Información del tratamiento
                    </h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <x-input-label for="tratamiento" value="Nombre del tratamiento" />
                        <x-text-input id="tratamiento"
                                      type="text"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->tratamiento"/>
                    </div>

                    <div>
                        <x-input-label for="fecha_asignacion" value="Fecha de asignación" />
                        <x-text-input id="fecha_asignacion"
                                      type="date"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->fecha_asignacion->format('Y-m-d')"/>
                    </div>

                    <div>
                        <x-input-label for="paciente" value="Paciente" />
                        <x-text-input id="paciente"
                                      type="text"
                                      class="w-full mt-1"
                                      disabled
                                      :value="($tratamiento->paciente->usuarioAcceso->name ?? 'Paciente sin usuario') . ' (' . $tratamiento->paciente->nuhsa . ')'" />
                    </div>

                    <div>
                        <x-input-label for="duracion_total" value="Duración total (días)" />
                        <x-text-input id="duracion_total"
                                      type="text"
                                      class="w-full mt-1"
                                      disabled
                                      :value="$tratamiento->duracion_total . ' días'"/>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="descripcion" value="Descripción" />
                        <textarea id="descripcion"
                                  class="w-full mt-1 rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-700"
                                  rows="5"
                                  disabled>{{ $tratamiento->descripcion }}</textarea>
                    </div>

                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end border-t border-gray-200">
                    <a href="{{ route('tratamientos.index') }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>
