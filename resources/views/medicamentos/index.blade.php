<x-medico-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Lista de Medicamentos</h3>
                    <a href="{{ route('medicamentos.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        + Crear medicamento
                    </a>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-blue-100 text-gray-900 text-sm">
                            <tr>
                                <th class="py-3 px-4 border-b text-left">Nombre común</th>
                                <th class="py-3 px-4 border-b text-left">Dosis</th>
                                <th class="py-3 px-4 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach ($medicamentos as $medicamento)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $medicamento->nombre }}</td>
                                    <td class="py-3 px-4">{{ $medicamento->miligramos }} mg</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('medicamentos.show', $medicamento->id) }}"
                                                class="text-gray-600 hover:text-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                              c4.478 0 8.268 2.943 9.542 7
                                                              -1.274 4.057-5.064 7-9.542 7
                                                              -4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('medicamentos.edit', $medicamento->id) }}"
                                                class="text-gray-600 hover:text-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                                                 a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <form id="delete-form-{{ $medicamento->id }}" method="POST"
                                                action="{{ route('medicamentos.destroy', $medicamento->id) }}"
                                                onsubmit="return confirm('¿Eliminar este medicamento?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-gray-600 hover:text-blue-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                     a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                                     m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($medicamentos->hasPages())
                        <div class="p-4">
                            {{ $medicamentos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>