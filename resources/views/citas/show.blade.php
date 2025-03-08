<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('citas.index') }}">Citas</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">Ver cita</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Información de la cita
                </div>
                <div class="p-6 bg-white border-b border-gray-200">

                        <div class="mt-4">
                            <x-input-label for="fecha_contratacion" :value="__('Fecha y hora')" />

                            <x-text-input id="fecha_hora" class="block mt-1 w-full"
                                     type="datetime-local"
                                     name="fecha_hora"
                                     disabled
                                     :value="$cita->fecha_hora->format('Y-m-d\TH:i:s')"
                                     required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="paciente_id" :value="__('Paciente')" />

                                <x-text-input class="block mt-1 w-full"
                                         type="text"
                                         disabled
                                         value="{{$cita->paciente->user->name}} ({{$cita->paciente->nuhsa}})"
                                />

                        </div>

                        <div class="mt-4">
                            <x-input-label for="medico_id" :value="__('Médico')" />
                                <x-text-input class="block mt-1 w-full"
                                         type="text"
                                         disabled
                                         value="{{$cita->medico->user->name}} ({{$cita->medico->especialidad->nombre}})"
                                />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-button type="button">
                                <a href={{route('citas.index')}}>
                                    {{ __('Volver') }}
                                </a>
                            </x-danger-button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Prescripciones
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Medicamento</th>
                            <th class="py-3 px-6 text-left">Inicio</th>
                            <th class="py-3 px-6 text-left">Fin</th>
                            <th class="py-3 px-6 text-left">Tomas/día</th>
                            <th class="py-3 px-6 text-left">Comentarios</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($cita->medicamentos as $medicamento)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$medicamento->nombre}} ({{$medicamento->miligramos}} {{__('mg')}})</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$medicamento->pivot->inicio->format('d/m/Y')}} </span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$medicamento->pivot->fin->format('d/m/Y')}} </span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$medicamento->pivot->tomas_dia}} </span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{$medicamento->pivot->comentarios}} </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
