<x-medico-layout>
    <x-slot name="header">
        <div class="p-4 bg-blue-600 text-white flex justify-between items-center rounded-md shadow-sm">
            <h3 class="text-lg font-semibold tracking-wide">Listado de Síntomas Normalizados</h3>
            <a href="{{ route('sintomas.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
                + Nuevo Síntoma
            </a>
        </div>
    </x-slot>

    <div class="py-1 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg border border-gray-200">

                {{-- Filtro por órgano --}}
                <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                    <form method="GET" action="{{ route('sintomas.index') }}" class="flex items-center gap-3">
                        <label for="organo" class="font-semibold text-gray-700">Filtrar por órgano:</label>
                        <select name="organo" id="organo" onchange="this.form.submit()"
                            class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach ($organos as $organo)
                                <option value="{{ $organo->id }}" {{ request('organo') == $organo->id ? 'selected' : '' }}>
                                    {{ $organo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Listado de síntomas --}}
                <div class="p-6">
                    @forelse ($organos as $organo)
                        @php
                            $sintomasPorOrgano = $sintomas->where('organo_id', $organo->id);
                        @endphp

                        @if ($sintomasPorOrgano->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-blue-800 mb-3 border-b border-blue-200 pb-1">
                                    {{ $organo->nombre }}
                                </h4>

                                @foreach ($sintomasPorOrgano->groupBy('categoria') as $categoria => $grupo)
                                    <div class="ml-3 mb-3">
                                        <h5 class="text-md font-semibold text-gray-700 mb-2">
                                            Categoría:
                                            <span class="text-blue-600">
                                                {{ $categoria && trim($categoria) !== '' ? $categoria : 'Sin categoría' }}
                                            </span>
                                        </h5>

                                        <table class="min-w-full border border-gray-200 rounded-md mb-3">
                                            <thead class="bg-blue-50 text-gray-700 text-sm font-semibold">
                                                <tr>
                                                    <th class="py-2 px-3 text-left border-b">ID</th>
                                                    <th class="py-2 px-3 text-left border-b">Síntoma</th>
                                                    <th class="py-2 px-3 text-left border-b">Manifestación clínica</th>
                                                    <th class="py-2 px-3 text-left border-b text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-700 text-sm">
                                                @foreach ($grupo as $sintoma)
                                                    <tr class="hover:bg-blue-50 transition">
                                                        <td class="py-2 px-3 border-b">{{ $sintoma->id }}</td>
                                                        <td class="py-2 px-3 border-b">{{ $sintoma->sintoma }}</td>
                                                        <td class="py-2 px-3 border-b">{{ $sintoma->manif_clinica }}</td>
                                                        <td class="py-3 px-4 text-center">
                                                            <div class="flex justify-center space-x-3">

                                                                <a href="{{ route('sintomas.show', $sintoma->id) }}"
                                                                    class="text-blue-600 hover:text-blue-800" title="Ver">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>

                                                                @if(Auth::user()->es_medico)
                                                                    <a href="{{ route('sintomas.edit', $sintoma->id) }}"
                                                                        class="text-yellow-600 hover:text-yellow-700" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>

                                                                    <form id="delete-form-{{ $sintoma->id }}" method="POST"
                                                                        action="{{ route('sintomas.destroy', $sintoma->id) }}"
                                                                        onsubmit="return confirm('¿Eliminar este registro?')">

                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit" class="text-red-600 hover:text-red-800"
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
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <p class="text-gray-500">No hay síntomas registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>