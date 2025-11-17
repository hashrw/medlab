<x-medico-layout>

    {{-- CABECERA --}}
    <div class="p-4 bg-blue-600 text-white flex justify-between items-center rounded-md shadow-sm mb-4">
        <h3 class="text-lg font-semibold tracking-wide">Lista de Medicamentos</h3>

        <a href="{{ route('medicamentos.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
            + Crear medicamento
        </a>
    </div>

    <div class="py-1 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CONTENIDO --}}
                <div class="overflow-x-auto p-6">

                    <table class="min-w-full border border-gray-200 rounded-md">
                        <thead class="bg-blue-50 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th class="py-2 px-3 text-left border-b">Nombre común</th>
                                <th class="py-2 px-3 text-left border-b">Dosis</th>
                                <th class="py-2 px-3 text-center border-b">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700 text-sm">
                            @foreach ($medicamentos as $medicamento)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="py-3 px-4 border-b">{{ $medicamento->nombre }}</td>
                                    <td class="py-3 px-4 border-b">{{ $medicamento->miligramos }} mg</td>

                                    {{-- ACCIONES --}}
                                    <td class="py-3 px-4 text-center border-b">
                                        <div class="flex justify-center space-x-3">

                                            {{-- Ver --}}
                                            <a href="{{ route('medicamentos.show', $medicamento->id) }}"
                                                class="text-blue-600 hover:text-blue-800"
                                                title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Editar --}}
                                            <a href="{{ route('medicamentos.edit', $medicamento->id) }}"
                                                class="text-yellow-600 hover:text-yellow-700"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Eliminar --}}
                                            <form id="delete-form-{{ $medicamento->id }}" method="POST"
                                                  action="{{ route('medicamentos.destroy', $medicamento->id) }}"
                                                  onsubmit="return confirm('¿Eliminar este medicamento?');">
                                                @csrf
                                                @method('delete')

                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
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
