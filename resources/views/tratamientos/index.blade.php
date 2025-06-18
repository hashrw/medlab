<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex items-center mt-4 ml-2">
                    <form method="GET" action="{{ route('tratamientos.create') }}">
                        <x-primary-button type="submit" class="ml-4">
                            {{ __('Crear Tratamiento') }}
                        </x-primary-button>
                    </form>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nombre del Tratamiento</th>
                            <th class="py-3 px-6 text-left">Fecha de Asignación</th>
                            <th class="py-3 px-6 text-left">Duración (días)</th>
                            @if(!Auth::user()->es_medico)
                            <th class="py-3 px-6 text-left">Paciente</th>
                            @endif
                            @if(!Auth::user()->es_paciente)
                            <th class="py-3 px-6 text-left">Médico</th>
                            @endif
                            <th class="py-3 px-6 text-left">Líneas de Tratamiento</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">

                        @foreach ($tratamientos as $tratamiento)
                            <tr class="border-b border-gray-200">
                                <!-- Nombre del Tratamiento -->
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $tratamiento->tratamiento }}</span>
                                    </div>
                                </td>

                                <!-- Fecha de Asignación -->
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $tratamiento->fecha_asignacion->format('d/m/Y') }}</span>
                                    </div>
                                </td>

                                <!-- Duración del Tratamiento -->
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $tratamiento->duracion_trat }} días</span>
                                    </div>
                                </td>

                                <!-- Paciente (si el usuario no es médico) -->
                                @if(!Auth::user()->es_medico)
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $tratamiento->paciente->user->name }}</span>
                                    </div>
                                </td>
                                @endif

                                <!-- Líneas de Tratamiento -->
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $tratamiento->lineasTratamiento->count() }}</span>
                                    </div>
                                </td>

                                <!-- Botones de Acción -->
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-end">
                                        <!-- Ver -->
                                        <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <a href="{{ route('tratamientos.show', $tratamiento->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Editar -->
                                        <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <a href="{{ route('tratamientos.edit', $tratamiento->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Eliminar -->
                                        <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <form id="delete-form-{{ $tratamiento->id }}" method="POST" action="{{ route('tratamientos.destroy', $tratamiento->id) }}">
                                                @csrf
                                                @method('delete')
                                                <a class="cursor-pointer" onclick="getElementById('delete-form-{{ $tratamiento->id }}').submit();">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    {{ $tratamientos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>