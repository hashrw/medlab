<x-medico-layout> 
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Módulo de Diagnósticos</h3>
                    @if (Auth::user()->es_medico)
                        <a href="{{ route('diagnosticos.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            + Nuevo Diagnóstico
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-blue-100 text-gray-900">
                            <tr class="text-sm leading-normal text-left">
                                <th class="py-3 px-4 border-b">ID</th>
                                <th class="py-3 px-4 border-b">Enfermedad</th>
                                <th class="py-3 px-4 border-b">Estado</th>
                                <th class="py-3 px-4 border-b">Fecha de trasplante</th>
                                <th class="py-3 px-4 border-b">Origen</th>
                                <th class="py-3 px-4 border-b">Recomendación</th>
                                <th class="py-3 px-4 border-b">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach ($diagnosticos as $diagnostico)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $diagnostico->id }}</td>
                                    <td class="py-3 px-4">{{ $diagnostico->tipo_enfermedad }}</td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">
                                                {{ $diagnostico->estado->estado ?? __('Sin registro') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">{{ $diagnostico->f_trasplante }}</td>
                                    <td class="py-3 px-4">
                                        {{ $diagnostico->origen ?? 'Manual' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($diagnostico->regla && $diagnostico->regla->tipo_recomendacion)
                                            {{ $diagnostico->regla->tipo_recomendacion }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-end">
                                            <div class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                                <a href="{{ route('diagnosticos.show', $diagnostico->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>

                                            <div class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                                <a href="{{ route('diagnosticos.edit', $diagnostico->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                                <form id="delete-form-{{ $diagnostico->id }}" method="POST"
                                                    action="{{ route('diagnosticos.destroy', $diagnostico->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <a class="cursor-pointer"
                                                        onclick="getElementById('delete-form-{{ $diagnostico->id }}').submit();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    <div class="p-4">
                        {{ $diagnosticos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>
