<x-medico-layout>

    <div class="py-3 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-4 bg-blue-700 text-white flex justify-between items-center rounded-t-lg">
                    <h3 class="p-6 bg-blue-800 text-white">
                        Lista de medicamentos
                    </h3>

                    <a href="{{ route('medicamentos.create') }}"
                        class="p-6 bg-blue-800 text-white">
                        + Crear medicamento
                    </a>
                </div>

                {{-- CONTENIDO --}}
                <div class="p-6">

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-blue-50 text-gray-700 text-sm font-semibold">
                                <tr>
                                    <th class="py-3 px-4 text-left border-b">Nombre común</th>
                                    <th class="py-3 px-4 text-left border-b">Dosis</th>
                                    <th class="py-3 px-4 text-center border-b">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-700 text-sm bg-white">
                                @foreach ($medicamentos as $medicamento)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="py-3 px-4 border-b">
                                            {{ $medicamento->nombre }}
                                        </td>

                                        <td class="py-3 px-4 border-b">
                                            {{ $medicamento->miligramos }} mg
                                        </td>

                                        <td class="py-3 px-4 text-center border-b">
                                            <div class="flex justify-center space-x-3">

                                                <a href="{{ route('medicamentos.show', $medicamento->id) }}"
                                                    class="text-blue-600 hover:text-blue-800" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('medicamentos.edit', $medicamento->id) }}"
                                                    class="text-yellow-600 hover:text-yellow-700" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form id="delete-form-{{ $medicamento->id }}" method="POST"
                                                    action="{{ route('medicamentos.destroy', $medicamento->id) }}"
                                                    onsubmit="return confirm('¿Eliminar este medicamento?');">
                                                    @csrf
                                                    @method('delete')

                                                    <button type="submit" class="text-red-600 hover:text-red-800"
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
                    </div>

                    @if ($medicamentos->hasPages())
                        <div class="pt-4">
                            {{ $medicamentos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>