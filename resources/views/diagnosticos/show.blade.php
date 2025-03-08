<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('diagnosticos.index') }}">Diagnósticos</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">Ver Diagnóstico</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Información del Diagnóstico
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Campos del diagnóstico -->
                    <div class="mt-4">
                        <x-input-label for="paciente_id" :value="__('Paciente')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->paciente->user->name }} ({{ $diagnostico->paciente->nuhsa }})"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="enfermedad_id" :value="__('Enfermedad')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->enfermedad->nombre }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="dias_desde_trasplante" :value="__('Días desde Trasplante')" />
                        <x-text-input class="block mt-1 w-full"
                                type="number"
                                disabled
                                value="{{ $diagnostico->dias_desde_trasplante }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="tipo_enfermedad" :value="__('Tipo de Enfermedad')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->tipo_enfermedad }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="estado_enfermedad" :value="__('Estado de Enfermedad')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->estado_enfermedad }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="comienzo_cronica" :value="__('Comienzo Crónica')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->comienzo_cronica }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="escala_karnofsky" :value="__('Escala Karnofsky')" />
                        <x-text-input class="block mt-1 w-full"
                                type="number"
                                disabled
                                value="{{ $diagnostico->escala_karnofsky }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="estado_injerto" :value="__('Estado del Injerto')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->estado_injerto }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="tipo_infeccion" :value="__('Tipo de Infección')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->tipo_infeccion }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="f_hospitalizacion" :value="__('Fecha de Hospitalización')" />
                        <x-text-input class="block mt-1 w-full"
                                type="datetime-local"
                                disabled
                                value="{{ $diagnostico->f_hospitalizacion->format('Y-m-d\TH:i:s') }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="f_electromiografia" :value="__('Fecha de Electromiografía')" />
                        <x-text-input class="block mt-1 w-full"
                                type="datetime-local"
                                disabled
                                value="{{ $diagnostico->f_electromiografia->format('Y-m-d\TH:i:s') }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="f_eval_injerto" :value="__('Fecha de Evaluación del Injerto')" />
                        <x-text-input class="block mt-1 w-full"
                                type="datetime-local"
                                disabled
                                value="{{ $diagnostico->f_eval_injerto->format('Y-m-d\TH:i:s') }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="f_medulograma" :value="__('Fecha de Medulograma')" />
                        <x-text-input class="block mt-1 w-full"
                                type="datetime-local"
                                disabled
                                value="{{ $diagnostico->f_medulograma->format('Y-m-d\TH:i:s') }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="f_espirometria" :value="__('Fecha de Espirometría')" />
                        <x-text-input class="block mt-1 w-full"
                                type="datetime-local"
                                disabled
                                value="{{ $diagnostico->f_espirometria->format('Y-m-d\TH:i:s') }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="f_esplenectomia" :value="__('Fecha de Esplenectomía')" />
                        <x-text-input class="block mt-1 w-full"
                                type="datetime-local"
                                disabled
                                value="{{ $diagnostico->f_esplenectomia->format('Y-m-d\TH:i:s') }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="hipoalbuminemia" :value="__('Hipoalbuminemia')" />
                        <x-text-input class="block mt-1 w-full"
                                type="text"
                                disabled
                                value="{{ $diagnostico->hipoalbuminemia ? 'Sí' : 'No' }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="observaciones" :value="__('Observaciones')" />
                        <textarea class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                disabled
                        >{{ $diagnostico->observaciones }}</textarea>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-danger-button type="button">
                            <a href="{{ route('diagnosticos.index') }}">
                                {{ __('Volver') }}
                            </a>
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Síntomas -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Síntomas
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Síntoma</th>
                                <th class="py-3 px-6 text-left">Fecha de Diagnóstico</th>
                                <th class="py-3 px-6 text-left">Score NIH</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($diagnostico->sintomas as $sintoma)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $sintoma->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $sintoma->pivot->fecha_diagnostico->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $sintoma->pivot->score_nih }}</span>
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