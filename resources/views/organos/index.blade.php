<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zonas de afectación') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                <!-- Cabecera azul -->
                <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Lista de Zonas de Afectación</h3>
                    <a href="{{ route('organos.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        + Crear nuevo registro
                    </a>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-blue-100 text-gray-900 text-sm">
                            <tr>
                                <th class="py-3 px-4 border-b text-left">Órgano o zona de afectación</th>
                                <th class="py-3 px-4 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach ($organos as $organo)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $organo->nombre }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                        <a href="{{ route('organos.show', $organo->id) }}" class="text-gray-600 hover:text-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                          c4.478 0 8.268 2.943 9.542 7
                                                          -1.274 4.057-5.064 7-9.542 7
                                                          -4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('organos.edit', $organo->id) }}"
                                               class="text-gray-600 hover:text-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                                             a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                            </a>

                                            <form id="delete-form-{{ $organo->id }}"
                                                  method="POST"
                                                  action="{{ route('organos.destroy', $organo->id) }}"
                                                  onsubmit="return confirm('¿Eliminar esta zona de afectación?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-gray-600 hover:text-blue-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                 a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                                 m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($organos->hasPages())
                        <div class="p-4">
                            {{ $organos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
