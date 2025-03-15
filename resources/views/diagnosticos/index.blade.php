<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Diagnósticos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(Auth::user()->es_medico)
                    <div class="flex items-center mt-4 ml-2">
                        <form method="GET" action="{{ route('diagnosticos.create') }}">
                            <x-primary-button type="submit" class="ml-4">
                                {{ __('Crear Diagnóstico') }}
                            </x-primary-button>
                        </form>
                    </div>
                @endif
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                @if(Auth::user()->es_medico)
                                    <th class="py-3 px-6 text-left">Paciente</th>
                                @endif
                                <th class="py-3 px-6 text-left">Enfermedad</th>
                                <th class="py-3 px-6 text-left">Días desde Trasplante</th>
                                <th class="py-3 px-6 text-left">Tipo de Enfermedad</th>
                                <th class="py-3 px-6 text-left">Estado de Enfermedad</th>
                                <th class="py-3 px-6 text-left">Comienzo Crónica</th>
                                <th class="py-3 px-6 text-left">Escala Karnofsky</th>
                                <th class="py-3 px-6 text-left">Estado del Injerto</th>
                                <th class="py-3 px-6 text-left">Tipo de Infección</th>
                                <th class="py-3 px-6 text-left">Fecha Hospitalización</th>
                                <th class="py-3 px-6 text-left">Fecha Electromiografía</th>
                                <th class="py-3 px-6 text-left">Fecha Evaluación Injerto</th>
                                <th class="py-3 px-6 text-left">Fecha Medulograma</th>
                                <th class="py-3 px-6 text-left">Fecha Espirometría</th>
                                <th class="py-3 px-6 text-left">Fecha Esplenectomía</th>
                                <th class="py-3 px-6 text-left">Hipoalbuminemia</th>
                                <th class="py-3 px-6 text-left">Observaciones</th>
                                <th class="py-3 px-6 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($diagnosticos as $diagnostico)
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->id }}</span>
                                        </div>
                                    </td>
                                    @if(Auth::user()->es_medico)
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="font-medium">{{ $diagnostico->paciente->user->name }}</span>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->enfermedad->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->dias_desde_trasplante }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->tipo_enfermedad }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->estado_enfermedad }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->comienzo_cronica }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->escala_karnofsky }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->estado_injerto }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->tipo_infeccion }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->f_hospitalizacion->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->f_electromiografia->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->f_eval_injerto->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->f_medulograma->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->f_espirometria->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->f_esplenectomia->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->hipoalbuminemia ? 'Sí' : 'No' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $diagnostico->observaciones }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-end">
                                            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                <a href="{{ route('diagnosticos.show', $diagnostico->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                            @if(Auth::user()->es_medico)
                                                <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                    <a href="{{ route('diagnosticos.edit', $diagnostico->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                    <form id="delete-form-{{ $diagnostico->id }}" method="POST" action="{{ route('diagnosticos.destroy', $diagnostico->id) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a class="cursor-pointer" onclick="getElementById('delete-form-{{ $diagnostico->id }}').submit();">
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
                    {{ $diagnosticos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>