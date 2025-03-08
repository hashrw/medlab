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
                    <a href="#" class="text-gray-500" aria-current="page">Crear nuevo registro</a>
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
                    <form method="POST" action="{{ route('diagnosticos.store') }}">
                        @csrf

                        <!-- Paciente -->
                        <div class="mt-4">
                            <x-input-label for="paciente_id" :value="__('Paciente')" />
                            @isset($paciente)
                                <x-text-input id="paciente_id" class="block mt-1 w-full"
                                             type="hidden"
                                             name="paciente_id"
                                             :value="$paciente->id"
                                             required />
                                <x-text-input class="block mt-1 w-full"
                                             type="text"
                                             disabled
                                             value="{{$paciente->user->name}} ({{$paciente->nuhsa}})"
                                />
                            @else
                                <x-select id="paciente_id" name="paciente_id" required>
                                    <option value="">{{__('Elige un paciente')}}</option>
                                    @foreach ($pacientes as $paciente)
                                        <option value="{{$paciente->id}}" @if (old('paciente_id') == $paciente->id) selected @endif>{{$paciente->user->name}} ({{$paciente->nuhsa}})</option>
                                    @endforeach
                                </x-select>
                            @endisset
                        </div>

                        <!-- Médico -->
                        <div class="mt-4">
                            <x-input-label for="medico_id" :value="__('Médico')" />
                            @isset($medico)
                                <x-text-input id="medico_id" class="block mt-1 w-full"
                                             type="hidden"
                                             name="medico_id"
                                             :value="$medico->id"
                                             required />
                                <x-text-input class="block mt-1 w-full"
                                             type="text"
                                             disabled
                                             value="{{$medico->user->name}} ({{$medico->especialidad->nombre}})"
                                />
                            @else
                                <x-select id="medico_id" name="medico_id" required>
                                    <option value="">{{__('Elige un médico')}}</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{$medico->id}}" @if (old('medico_id') == $medico->id) selected @endif>{{$medico->user->name}} ({{$medico->especialidad->nombre}})</option>
                                    @endforeach
                                </x-select>
                            @endisset
                        </div>

                        <!-- Enfermedad -->
                        <div class="mt-4">
                            <x-input-label for="enfermedad_id" :value="__('Enfermedad')" />
                            <x-select id="enfermedad_id" name="enfermedad_id" required>
                                <option value="">{{__('Elige una enfermedad')}}</option>
                                @foreach ($enfermedades as $enfermedad)
                                    <option value="{{$enfermedad->id}}" @if (old('enfermedad_id') == $enfermedad->id) selected @endif>{{$enfermedad->nombre}}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Días desde Trasplante -->
                        <div class="mt-4">
                            <x-input-label for="dias_desde_trasplante" :value="__('Días desde Trasplante')" />
                            <x-text-input id="dias_desde_trasplante" name="dias_desde_trasplante" type="number" class="block mt-1 w-full" :value="old('dias_desde_trasplante')" />
                        </div>

                        <!-- Tipo de Enfermedad -->
                        <div class="mt-4">
                            <x-input-label for="tipo_enfermedad" :value="__('Tipo de Enfermedad')" />
                            <x-select id="tipo_enfermedad" name="tipo_enfermedad" required>
                                <option value="">{{__('Elige un tipo')}}</option>
                                <option value="aguda" @if (old('tipo_enfermedad') == 'aguda') selected @endif>Aguda</option>
                                <option value="cronica" @if (old('tipo_enfermedad') == 'cronica') selected @endif>Crónica</option>
                            </x-select>
                        </div>

                        <!-- Estado de la Enfermedad -->
                        <div class="mt-4">
                            <x-input-label for="estado_enfermedad" :value="__('Estado de la Enfermedad')" />
                            <x-select id="estado_enfermedad" name="estado_enfermedad" required>
                                <option value="">{{__('Elige un estado')}}</option>
                                <option value="activa" @if (old('estado_enfermedad') == 'activa') selected @endif>Activa</option>
                                <option value="remision" @if (old('estado_enfermedad') == 'remision') selected @endif>Remisión</option>
                            </x-select>
                        </div>

                        <!-- Comienzo de Enfermedad Crónica -->
                        <div class="mt-4">
                            <x-input-label for="comienzo_cronica" :value="__('Comienzo de Enfermedad Crónica')" />
                            <x-text-input id="comienzo_cronica" name="comienzo_cronica" type="date" class="block mt-1 w-full" :value="old('comienzo_cronica')" />
                        </div>

                        <!-- Escala de Karnofsky -->
                        <div class="mt-4">
                            <x-input-label for="escala_karnofsky" :value="__('Escala de Karnofsky')" />
                            <x-text-input id="escala_karnofsky" name="escala_karnofsky" type="number" class="block mt-1 w-full" :value="old('escala_karnofsky')" />
                        </div>

                        <!-- Estado del Injerto -->
                        <div class="mt-4">
                            <x-input-label for="estado_injerto" :value="__('Estado del Injerto')" />
                            <x-select id="estado_injerto" name="estado_injerto" required>
                                <option value="">{{__('Elige un estado')}}</option>
                                <option value="estable" @if (old('estado_injerto') == 'estable') selected @endif>Estable</option>
                                <option value="rechazo" @if (old('estado_injerto') == 'rechazo') selected @endif>Rechazo</option>
                            </x-select>
                        </div>

                        <!-- Tipo de Infección -->
                        <div class="mt-4">
                            <x-input-label for="tipo_infeccion" :value="__('Tipo de Infección')" />
                            <x-select id="tipo_infeccion" name="tipo_infeccion" required>
                                <option value="">{{__('Elige un tipo')}}</option>
                                <option value="bacteriana" @if (old('tipo_infeccion') == 'bacteriana') selected @endif>Bacteriana</option>
                                <option value="viral" @if (old('tipo_infeccion') == 'viral') selected @endif>Viral</option>
                                <option value="fungica" @if (old('tipo_infeccion') == 'fungica') selected @endif>Fúngica</option>
                            </x-select>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Fecha de Hospitalización -->
                            <div>
                                <x-input-label for="f_hospitalizacion" :value="__('Fecha de Hospitalización')" />
                                <x-text-input id="f_hospitalizacion" name="f_hospitalizacion" type="datetime-local" class="block mt-1 w-full" :value="old('f_hospitalizacion')" />
                            </div>

                            <!-- Fecha de Electromiografía -->
                            <div>
                                <x-input-label for="f_electromiografia" :value="__('Fecha de Electromiografía')" />
                                <x-text-input id="f_electromiografia" name="f_electromiografia" type="datetime-local" class="block mt-1 w-full" :value="old('f_electromiografia')" />
                            </div>

                            <!-- Fecha de Evaluación del Injerto -->
                            <div>
                                <x-input-label for="f_eval_injerto" :value="__('Fecha de Evaluación del Injerto')" />
                                <x-text-input id="f_eval_injerto" name="f_eval_injerto" type="datetime-local" class="block mt-1 w-full" :value="old('f_eval_injerto')" />
                            </div>

                            <!-- Fecha de Medulograma -->
                            <div>
                                <x-input-label for="f_medulograma" :value="__('Fecha de Medulograma')" />
                                <x-text-input id="f_medulograma" name="f_medulograma" type="datetime-local" class="block mt-1 w-full" :value="old('f_medulograma')" />
                            </div>

                            <!-- Fecha de Espirometría -->
                            <div>
                                <x-input-label for="f_espirometria" :value="__('Fecha de Espirometría')" />
                                <x-text-input id="f_espirometria" name="f_espirometria" type="datetime-local" class="block mt-1 w-full" :value="old('f_espirometria')" />
                            </div>

                            <!-- Fecha de Esplenectomía -->
                            <div>
                                <x-input-label for="f_esplenectomia" :value="__('Fecha de Esplenectomía')" />
                                <x-text-input id="f_esplenectomia" name="f_esplenectomia" type="datetime-local" class="block mt-1 w-full" :value="old('f_esplenectomia')" />
                            </div>
                        </div>

                        <!-- Hipoalbuminemia -->
                        <div class="mt-4">
                            <x-input-label for="hipoalbuminemia" :value="__('Hipoalbuminemia')" />
                            <x-select id="hipoalbuminemia" name="hipoalbuminemia" required>
                                <option value="">{{__('Elige una opción')}}</option>
                                <option value="si" @if (old('hipoalbuminemia') == 'si') selected @endif>Sí</option>
                                <option value="no" @if (old('hipoalbuminemia') == 'no') selected @endif>No</option>
                            </x-select>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-4">
                            <x-input-label for="observaciones" :value="__('Observaciones')" />
                            <textarea id="observaciones" name="observaciones" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('observaciones') }}</textarea>
                        </div>

                        <!-- Síntomas (Relación N a N) -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900">Síntomas</h3>
                            <div id="sintomas-container">
                                @foreach ($sintomas as $sintoma)
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 border p-4 rounded-lg">
                                        <!-- Síntoma -->
                                        <div class="mb-4">
                                            <x-input-label for="sintomas[{{ $sintoma->id }}][sintoma_id]" :value="__('Síntoma')" />
                                            <x-text-input id="sintomas[{{ $sintoma->id }}][sintoma_id]" name="sintomas[{{ $sintoma->id }}][sintoma_id]" type="hidden" value="{{ $sintoma->id }}" />
                                            <x-text-input class="block mt-1 w-full" type="text" disabled value="{{ $sintoma->nombre }}" />
                                        </div>

                                        <!-- Fecha de Diagnóstico -->
                                        <div class="mb-4">
                                            <x-input-label for="sintomas[{{ $sintoma->id }}][fecha_diagnostico]" :value="__('Fecha de Diagnóstico')" />
                                            <x-text-input id="sintomas[{{ $sintoma->id }}][fecha_diagnostico]" name="sintomas[{{ $sintoma->id }}][fecha_diagnostico]" type="date" class="block mt-1 w-full" />
                                        </div>

                                        <!-- Score NIH -->
                                        <div class="mb-4">
                                            <x-input-label for="sintomas[{{ $sintoma->id }}][score_nih]" :value="__('Score NIH')" />
                                            <x-text-input id="sintomas[{{ $sintoma->id }}][score_nih]" name="sintomas[{{ $sintoma->id }}][score_nih]" type="number" class="block mt-1 w-full" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
</x-app-layout>