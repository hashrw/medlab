<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('diagnosticos.index') }}">Diagnósticos</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">Editar registro</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Información del diagnóstico
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Errores de validación en servidor -->
                    <x-input-error class="mb-4" :messages="$errors->all()" />
                    <form method="POST" action="{{ route('diagnosticos.update', $diagnostico->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Paciente -->
                        <div class="mt-4">
                            <x-input-label for="paciente_id" :value="__('Paciente')" />
                            <x-select id="paciente_id" name="paciente_id" class="block mt-1 w-full" required>
                                @foreach ($pacientes as $paciente)
                                    <option value="{{$paciente->id}}" @if($diagnostico->paciente_id == $paciente->id) selected @endif>{{$paciente->user->name}} ({{$paciente->nuhsa}})</option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Médico -->
                        <div class="mt-4">
                            <x-input-label for="medico_id" :value="__('Médico')" />
                            <x-select id="medico_id" name="medico_id" class="block mt-1 w-full" required>
                                @foreach ($medicos as $medico)
                                    <option value="{{$medico->id}}" @if($diagnostico->medico_id == $medico->id) selected @endif>{{$medico->user->name}} ({{$medico->especialidad->nombre}})</option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Enfermedad -->
                        <div class="mt-4">
                            <x-input-label for="enfermedad_id" :value="__('Enfermedad')" />
                            <x-select id="enfermedad_id" name="enfermedad_id" class="block mt-1 w-full" required>
                                @foreach ($enfermedades as $enfermedad)
                                    <option value="{{$enfermedad->id}}" @if($diagnostico->enfermedad_id == $enfermedad->id) selected @endif>{{$enfermedad->nombre_enfermedad}}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Días desde Trasplante -->
                        <div class="mt-4">
                            <x-input-label for="dias_desde_trasplante" :value="__('Días desde Trasplante')" />
                            <x-text-input id="dias_desde_trasplante" name="dias_desde_trasplante" type="number" class="block mt-1 w-full" :value="$diagnostico->dias_desde_trasplante" />
                        </div>

                        <!-- Tipo de Enfermedad -->
                        <div class="mt-4">
                            <x-input-label for="tipo_enfermedad" :value="__('Tipo de Enfermedad')" />
                            <x-select id="tipo_enfermedad" name="tipo_enfermedad" class="block mt-1 w-full" required>
                                <option value="aguda" @if($diagnostico->tipo_enfermedad == 'aguda') selected @endif>Aguda</option>
                                <option value="cronica" @if($diagnostico->tipo_enfermedad == 'cronica') selected @endif>Crónica</option>
                            </x-select>
                        </div>

                        <!-- Estado de la Enfermedad -->
                        <div class="mt-4">
                            <x-input-label for="estado_enfermedad" :value="__('Estado de la Enfermedad')" />
                            <x-select id="estado_enfermedad" name="estado_enfermedad" class="block mt-1 w-full" required>
                                <option value="activa" @if($diagnostico->estado_enfermedad == 'activa') selected @endif>Activa</option>
                                <option value="remision" @if($diagnostico->estado_enfermedad == 'remision') selected @endif>Remisión</option>
                            </x-select>
                        </div>

                        <!-- Comienzo de Enfermedad Crónica -->
                        <div class="mt-4">
                            <x-input-label for="comienzo_cronica" :value="__('Comienzo de Enfermedad Crónica')" />
                            <x-text-input id="comienzo_cronica" name="comienzo_cronica" type="date" class="block mt-1 w-full" :value="$diagnostico->comienzo_cronica" />
                        </div>

                        <!-- Escala de Karnofsky -->
                        <div class="mt-4">
                            <x-input-label for="escala_karnofsky" :value="__('Escala de Karnofsky')" />
                            <x-text-input id="escala_karnofsky" name="escala_karnofsky" type="number" class="block mt-1 w-full" :value="$diagnostico->escala_karnofsky" />
                        </div>

                        <!-- Estado del Injerto -->
                        <div class="mt-4">
                            <x-input-label for="estado_injerto" :value="__('Estado del Injerto')" />
                            <x-select id="estado_injerto" name="estado_injerto" class="block mt-1 w-full" required>
                                <option value="estable" @if($diagnostico->estado_injerto == 'estable') selected @endif>Estable</option>
                                <option value="rechazo" @if($diagnostico->estado_injerto == 'rechazo') selected @endif>Rechazo</option>
                            </x-select>
                        </div>

                        <!-- Tipo de Infección -->
                        <div class="mt-4">
                            <x-input-label for="tipo_infeccion" :value="__('Tipo de Infección')" />
                            <x-select id="tipo_infeccion" name="tipo_infeccion" class="block mt-1 w-full" required>
                                <option value="bacteriana" @if($diagnostico->tipo_infeccion == 'bacteriana') selected @endif>Bacteriana</option>
                                <option value="viral" @if($diagnostico->tipo_infeccion == 'viral') selected @endif>Viral</option>
                                <option value="fungica" @if($diagnostico->tipo_infeccion == 'fungica') selected @endif>Fúngica</option>
                            </x-select>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Fecha de Hospitalización -->
                            <div>
                                <x-input-label for="f_hospitalizacion" :value="__('Fecha de Hospitalización')" />
                                <x-text-input id="f_hospitalizacion" name="f_hospitalizacion" type="datetime-local" class="block mt-1 w-full" :value="$diagnostico->f_hospitalizacion ? $diagnostico->f_hospitalizacion->format('Y-m-d\TH:i') : ''" />
                            </div>

                            <!-- Fecha de Electromiografía -->
                            <div>
                                <x-input-label for="f_electromiografia" :value="__('Fecha de Electromiografía')" />
                                <x-text-input id="f_electromiografia" name="f_electromiografia" type="datetime-local" class="block mt-1 w-full" :value="$diagnostico->f_electromiografia ? $diagnostico->f_electromiografia->format('Y-m-d\TH:i') : ''" />
                            </div>

                            <!-- Fecha de Evaluación del Injerto -->
                            <div>
                                <x-input-label for="f_eval_injerto" :value="__('Fecha de Evaluación del Injerto')" />
                                <x-text-input id="f_eval_injerto" name="f_eval_injerto" type="datetime-local" class="block mt-1 w-full" :value="$diagnostico->f_eval_injerto ? $diagnostico->f_eval_injerto->format('Y-m-d\TH:i') : ''" />
                            </div>

                            <!-- Fecha de Medulograma -->
                            <div>
                                <x-input-label for="f_medulograma" :value="__('Fecha de Medulograma')" />
                                <x-text-input id="f_medulograma" name="f_medulograma" type="datetime-local" class="block mt-1 w-full" :value="$diagnostico->f_medulograma ? $diagnostico->f_medulograma->format('Y-m-d\TH:i') : ''" />
                            </div>

                            <!-- Fecha de Espirometría -->
                            <div>
                                <x-input-label for="f_espirometria" :value="__('Fecha de Espirometría')" />
                                <x-text-input id="f_espirometria" name="f_espirometria" type="datetime-local" class="block mt-1 w-full" :value="$diagnostico->f_espirometria ? $diagnostico->f_espirometria->format('Y-m-d\TH:i') : ''" />
                            </div>

                            <!-- Fecha de Esplenectomía -->
                            <div>
                                <x-input-label for="f_esplenectomia" :value="__('Fecha de Esplenectomía')" />
                                <x-text-input id="f_esplenectomia" name="f_esplenectomia" type="datetime-local" class="block mt-1 w-full" :value="$diagnostico->f_esplenectomia ? $diagnostico->f_esplenectomia->format('Y-m-d\TH:i') : ''" />
                            </div>
                        </div>

                        <!-- Hipoalbuminemia -->
                        <div class="mt-4">
                            <x-input-label for="hipoalbuminemia" :value="__('Hipoalbuminemia')" />
                            <x-select id="hipoalbuminemia" name="hipoalbuminemia" class="block mt-1 w-full" required>
                                <option value="si" @if($diagnostico->hipoalbuminemia == 'si') selected @endif>Sí</option>
                                <option value="no" @if($diagnostico->hipoalbuminemia == 'no') selected @endif>No</option>
                            </x-select>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-4">
                            <x-input-label for="observaciones" :value="__('Observaciones')" />
                            <textarea id="observaciones" name="observaciones" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ $diagnostico->observaciones }}</textarea>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end mt-8">
                            <x-danger-button type="button">
                                <a href="{{ route('diagnosticos.index') }}">{{ __('Cancelar') }}</a>
                            </x-danger-button>
                            <x-primary-button class="ml-4">
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Síntomas Asociados -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Síntomas asociados
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-900 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Síntoma</th>
                            <th class="py-3 px-6 text-left">Fecha de Diagnóstico</th>
                            <th class="py-3 px-6 text-left">Score NIH</th>
                            <th class="py-3 px-6 text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($diagnostico->sintomas as $sintoma)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $sintoma->sintoma }}</span>
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
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <!-- Botón para eliminar síntoma -->
                                        <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <form id="detach-form-{{ $diagnostico->id }}-{{ $sintoma->id }}" method="POST" action="{{ route('diagnosticos.detachSintoma', [$diagnostico->id, $sintoma->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <a class="cursor-pointer" onclick="getElementById('detach-form-{{ $diagnostico->id }}-{{ $sintoma->id }}').submit();">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Añadir Síntoma -->
    @if(!Auth::user()->es_paciente)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                        Añadir síntomas diagnosticados
                    </div>
                    <div class="p-6 bg-white border-b border-gray-200">
                    <x-input-error class="mb-4" :messages="$errors->attach->all()"/>
                    <form method="POST" action="{{ route('diagnosticos.attachSintoma', [$diagnostico->id]) }}">
                    @csrf
                            <div class="mt-4">
                                <x-input-label for="sintoma_id" :value="__('sintoma')"/>

                                <x-select id="sintoma_id" name="sintoma_id" required>
                                    <option value="">{{__('Elige un sintoma')}}</option>
                                    @foreach ($sintomas as $sintoma)
                                        <option value="{{$sintoma->id}}"
                                                @if (old('sintoma_id') == $sintoma->id) selected @endif>{{$sintoma->sintoma}}
                                            ({{$sintoma->sintoma}} {{__('.')}})
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>

                            <div class="mt-4">
                                <x-input-label for="fecha_diagnostico" :value="__('Fecha de diagnóstico')"/>

                                <x-text-input id="fecha_diagnostico" class="block mt-1 w-full"
                                              type="date"
                                              name="fecha_diagnostico"
                                              :value="old('fecha_diagnostico')"
                                              required/>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="score_nih" :value="__('Gravedad del síntoma')"/>

                                <x-select id="score_nih" name="score_nih" required>
                                    <option value="">{{__('Score síntoma')}}</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option :value="$i" @if (old('score_nih') == $i) selected @endif>{{$i}}</option>
                                    @endfor
                                </x-select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-danger-button type="button">
                                    <a href={{route('medicos.index')}}>
                                        {{ __('Cancelar') }}
                                    </a>
                                </x-danger-button>
                                <x-primary-button class="ml-4">
                                    {{ __('Guardar') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

                