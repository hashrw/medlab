<x-medico-layout>
    <div class="py-3 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-4 bg-blue-600 text-white flex justify-between items-center rounded-t-lg">
                    <h3 class="text-lg font-semibold tracking-wide">Listado de tratamientos</h3>

                    @if(Auth::user()->es_medico)
                        <a href="{{ route('tratamientos.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
                            + Crear nuevo registro
                        </a>
                    @endif
                </div>

                {{-- CONTENIDO --}}
                <div class="overflow-x-auto p-6">
                    <table class="min-w-full border border-gray-200 rounded-md">
                        <thead class="bg-blue-50 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th class="py-2 px-3 text-left border-b">Tratamiento</th>
                                <th class="py-2 px-3 text-left border-b">Fecha</th>
                                <th class="py-2 px-3 text-left border-b">Duración</th>

                                @if(Auth::user()->es_medico)
                                    <th class="py-2 px-3 text-left border-b">Paciente</th>
                                @endif

                                @if(Auth::user()->es_paciente)
                                    <th class="py-2 px-3 text-left border-b">Médico</th>
                                @endif

                                <th class="py-2 px-3 text-left border-b">Líneas</th>
                                <th class="py-2 px-3 text-center border-b">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700 text-sm">
                            @foreach($tratamientos as $tratamiento)
                                <tr class="hover:bg-blue-50 transition">

                                    {{-- Nombre --}}
                                    <td class="py-3 px-4 border-b font-semibold text-gray-900">
                                        {{ $tratamiento->tratamiento }}
                                    </td>

                                    {{-- Fecha --}}
                                    <td class="py-3 px-4 border-b">
                                        {{ $tratamiento->fecha_asignacion
                                            ? $tratamiento->fecha_asignacion->format('d/m/Y')
                                            : 'Sin fecha' }}
                                    </td>

                                    {{-- Duración --}}
                                    <td class="py-3 px-4 border-b">
                                        {{ $tratamiento->duracion_total }} días
                                    </td>

                                    {{-- Paciente (solo médicos) --}}
                                    @if(Auth::user()->es_medico)
                                        <td class="py-3 px-4 border-b">
                                            {{ $tratamiento->paciente->user->name }}
                                        </td>
                                    @endif

                                    {{-- Médico (solo pacientes) --}}
                                    @if(Auth::user()->es_paciente)
                                        <td class="py-3 px-4 border-b">
                                            {{ $tratamiento->medico->user->name }}
                                        </td>
                                    @endif

                                    {{-- Líneas --}}
                                    <td class="py-3 px-4 border-b">
                                        {{ $tratamiento->lineasTratamiento->count() }}
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="py-3 px-4 border-b text-center">
                                        <div class="flex justify-center space-x-3">

                                            {{-- Ver --}}
                                            <a href="{{ route('tratamientos.show', $tratamiento->id) }}"
                                               class="text-blue-600 hover:text-blue-800"
                                               title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(Auth::user()->es_medico)
                                                {{-- Editar --}}
                                                <a href="{{ route('tratamientos.edit', $tratamiento->id) }}"
                                                   class="text-yellow-600 hover:text-yellow-700"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Eliminar --}}
                                                <form method="POST"
                                                      action="{{ route('tratamientos.destroy', $tratamiento->id) }}"
                                                      onsubmit="return confirm('¿Eliminar este tratamiento?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Paginación --}}
                    <div class="p-4">
                        {{ $tratamientos->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-medico-layout>
