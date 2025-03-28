<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Síntomas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Botón para crear síntomas (solo para médicos) -->
                @if(Auth::user()->es_medico)
                    <div class="flex items-center mt-4 ml-2">
                        <form method="GET" action="{{ route('sintomas.create') }}">
                            <x-primary-button type="submit" class="ml-4">
                                {{ __('Crear síntoma') }}
                            </x-primary-button>
                        </form>
                    </div>
                @endif

                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Síntoma</th>
                            <th class="py-3 px-6 text-left">Manifestación clínica</th>
                            <th class="py-3 px-6 text-left">Órgano asociado</th>
                            @if(Auth::user()->es_medico)
                                <th class="py-3 px-6 text-left">Paciente</th>
                            @endif
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($sintomas as $sintoma)
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$sintoma->id}}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$sintoma->sintoma}}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$sintoma->manif_clinica}}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$sintoma->organo->nombre}}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-end">
                                        <!-- Botón para ver detalles -->
                                        <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <a href="{{route('sintomas.show', $sintoma->id)}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Botón para editar (solo para médicos) -->
                                        @if(Auth::user()->es_medico)
                                            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                <a href="{{route('sintomas.edit', $sintoma->id)}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif

                                        <!-- Botón para eliminar (solo para médicos) -->
                                        @if(Auth::user()->es_medico)
                                            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                <form id="delete-form-{{$sintoma->id}}" method="POST" action="{{ route('sintomas.destroy', $sintoma->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <a class="cursor-pointer" onclick="getElementById('delete-form-{{$sintoma->id}}').submit();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    {{ $sintomas->links() }} <!-- Paginación -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>