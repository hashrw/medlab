<x-medico-layout>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                @if(Auth::user()->es_medico)
                    <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Listado de pruebas clínicas</h3>
                        <form method="GET" action="{{ route('pruebas.create') }}">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Crear nuevo registro
                            </button>
                        </form>
                    </div>

                @else
                    <div class="p-6 bg-blue-800 text-white">
                        <h3 class="text-lg font-semibold">Listado de pruebas clínicas</h3>
                    </div>
                @endif

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg  overflow-x-auto w-full block">
                        <thead class="bg-blue-100 text-gray-900 text-sm">
                            <tr>
                                <th class="py-3 px-4 border-b">ID</th>
                                <th class="py-3 px-4 border-b">Nombre</th>
                                <th class="py-3 px-4 border-b">Tipo</th>
                                <th class="py-3 px-4 border-b">Fecha</th>
                                <th class="py-3 px-4 border-b">Resultado</th>
                                <th class="py-3 px-4 border-b">Comentario</th>
                                <th class="py-3 px-4 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($pruebas as $prueba)
                                <tr class="border-b hover:bg-gray-50 border-gray-200">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $prueba->id }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $prueba->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center max-w-xs truncate">
                                            <span class="font-medium">
                                                {{ $prueba->tipo_prueba ? $prueba->tipo_prueba->nombre : __('No especificado') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="font-medium">{{ \Carbon\Carbon::parse($prueba->fecha)->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $prueba->resultado }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $prueba->comentario }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-right whitespace-nowrap">
                                        <div class="flex item-center justify-end space-x-2">
                                            <div class="w-4 transform hover:text-purple-500 hover:scale-110">
                                                <a href="{{ route('pruebas.show', $prueba->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                      c4.478 0 8.268 2.943 9.542 7
                                                      -1.274 4.057-5.064 7-9.542 7
                                                      -4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>

                                            @if(Auth::user()->es_medico)
                                                <div class="w-4 transform hover:text-purple-500 hover:scale-110">
                                                    <a href="{{ route('pruebas.edit', $prueba->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                                                                a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                </div>

                                                <div class="w-4 transform hover:text-purple-500 hover:scale-110">
                                                    <form id="delete-form-{{ $prueba->id }}" method="POST"
                                                        action="{{ route('pruebas.destroy', $prueba->id) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="cursor-pointer"
                                                            onclick="getElementById('delete-form-{{ $prueba->id }}').submit();">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                                    a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                                                    m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </a>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-4">
                        {{ $pruebas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>