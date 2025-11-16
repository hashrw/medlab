<x-medico-layout>
    <x-slot name="header">
        <div class="p-4 bg-blue-600 text-white flex justify-between items-center rounded-md shadow-sm">
            <h3 class="text-lg font-semibold tracking-wide">Listado de Pruebas Clínicas</h3>
            <a href="{{ route('pruebas.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
                + Nueva prueba
            </a>
        </div>
    </x-slot>

    <div class="py-2 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- FILTRO POR TIPO DE PRUEBA --}}
            <div class="bg-white shadow-md rounded-lg border border-gray-200">
                <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                    <form method="GET" action="{{ route('pruebas.index') }}" class="flex items-center gap-3">

                        <label for="tipo" class="font-semibold text-gray-700">
                            Filtrar por tipo de prueba:
                        </label>

                        <select name="tipo" id="tipo" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm
                                       focus:ring-blue-500 focus:border-blue-500">

                            <option value="">Todos</option>

                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ request('tipo') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>

                    </form>
                </div>
            </div>

            @php
                $agrupadas = $pruebas->groupBy(function ($p) {
                    return optional($p->tipo_prueba)->nombre ?? 'Sin tipo definido';
                });
            @endphp

            @foreach ($agrupadas as $tipo => $lista)
                <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                    <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-700">
                            Tipo de Prueba: <span class="text-blue-600">{{ $tipo }}</span>
                        </h4>

                        <span class="text-sm text-gray-500">{{ $lista->count() }} registro(s)</span>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table class="min-w-full border-collapse w-full text-sm">
                            <thead class="bg-blue-100 text-gray-900">
                                <tr>
                                    <th class="py-3 px-4 border-b">ID</th>
                                    <th class="py-3 px-4 border-b">Nombre</th>
                                    <th class="py-3 px-4 border-b">Fecha</th>
                                    <th class="py-3 px-4 border-b">Resultado</th>
                                    <th class="py-3 px-4 border-b">Comentario</th>
                                    <th class="py-3 px-4 border-b text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-700">
                                @foreach ($lista as $prueba)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="py-3 px-4">{{ $prueba->id }}</td>
                                        <td class="py-3 px-4 font-medium">{{ $prueba->nombre }}</td>

                                        <td class="py-3 px-4">
                                            {{ \Carbon\Carbon::parse($prueba->fecha)->format('d/m/Y') }}
                                        </td>

                                        <td class="py-3 px-4">{{ $prueba->resultado }}</td>
                                        <td class="py-3 px-4">{{ $prueba->comentario }}</td>

                                        <td class="py-3 px-4 text-center">
                                            <div class="flex justify-center space-x-3">

                                                <a href="{{ route('pruebas.show', $prueba->id) }}"
                                                    class="text-blue-600 hover:text-blue-800" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if(Auth::user()->es_medico)
                                                    <a href="{{ route('pruebas.edit', $prueba->id) }}"
                                                        class="text-yellow-600 hover:text-yellow-700" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form id="delete-form-{{ $prueba->id }}" method="POST"
                                                        action="{{ route('pruebas.destroy', $prueba->id) }}"
                                                        onsubmit="return confirm('¿Eliminar esta prueba clínica?')">

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

                </div>
            @endforeach

            <div class="pt-4">
                {{ $pruebas->links() }}
            </div>

        </div>
    </div>

</x-medico-layout>  