<x-medico-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                @if(Auth::user()->es_medico)
                    <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Listado de tratamientos</h3>
                        <form method="GET" action="{{ route('tratamientos.create') }}">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Crear nuevo registro
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-6 bg-blue-800 text-white">
                        <h3 class="text-lg font-semibold">Listado de tratamientos</h3>
                    </div>
                @endif

                <!-- Contenido -->
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-x-auto w-full block">
                        <thead class="bg-blue-100 text-gray-900 text-sm">
                            <tr>
                                <th class="py-3 px-4 border-b">Nombre del Tratamiento</th>
                                <th class="py-3 px-4 border-b">Fecha de Asignación</th>
                                <th class="py-3 px-4 border-b">Duración (días)</th>
                                @if(Auth::user()->es_medico)
                                    <th class="py-3 px-4 border-b">Paciente</th>
                                @endif
                                @if(Auth::user()->es_paciente)
                                    <th class="py-3 px-4 border-b">Médico</th>
                                @endif
                                <th class="py-3 px-4 border-b">Líneas de Tratamiento</th>
                                <th class="py-3 px-4 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($tratamientos as $tratamiento)
                                <tr class="border-b hover:bg-gray-50 border-gray-200">
                                    <!-- Nombre -->
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $tratamiento->tratamiento }}</span>
                                        </div>
                                    </td>

                                    <!-- Fecha de asignación -->
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">
                                                {{ $tratamiento->fecha_asignacion ? $tratamiento->fecha_asignacion->format('d/m/Y') : 'Sin fecha' }}
                                            </span>

                                        </div>
                                    </td>

                                    <!-- Duración -->
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $tratamiento->duracion_total }} días</span>
                                        </div>
                                    </td>

                                    <!-- Paciente -->
                                    @if(Auth::user()->es_medico)
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="font-medium">{{ $tratamiento->paciente->user->name }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    <!-- Médico -->
                                    @if(Auth::user()->es_paciente)
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="font-medium">{{ $tratamiento->medico->user->name }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    <!-- Líneas de tratamiento -->
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $tratamiento->lineasTratamiento->count() }}</span>
                                        </div>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="py-3 px-6 text-center whitespace-nowrap">
                                        <div class="flex item-center justify-end space-x-2">
                                            <!-- Ver -->
                                            <a href="{{ route('tratamientos.show', $tratamiento->id) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if(Auth::user()->es_medico)
                                                <!-- Editar -->
                                                <a href="{{ route('tratamientos.edit', $tratamiento->id) }}"
                                                    class="text-green-600 hover:text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>

                                                <!-- Eliminar -->
                                                <form id="delete-form-{{ $tratamiento->id }}" method="POST"
                                                    action="{{ route('tratamientos.destroy', $tratamiento->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-4">
                        {{ $tratamientos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>