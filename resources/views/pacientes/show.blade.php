<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha Clínica del Paciente
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-start">

                    <div>
                        <h3 class="text-2xl font-bold">{{ $paciente->nombre }}</h3>

                        <p class="text-blue-100 mt-1">
                            NUHSA: <span class="font-semibold">{{ $paciente->nuhsa }}</span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Sexo: <span class="font-semibold">{{ $paciente->sexo }}</span>
                        </p>

                        <p class="text-blue-100 mt-1">
                            Edad:
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                            </span>
                        </p>
                    </div>

                    <div class="flex space-x-4 text-lg">
                        <a href="{{ route('pacientes.index') }}" class="hover:text-gray-200" title="Volver">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <a href="{{ route('pacientes.edit', $paciente->id) }}"
                           class="hover:text-yellow-300" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('pacientes.destroy', $paciente->id) }}"
                              onsubmit="return confirm('¿Eliminar este paciente?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="hover:text-red-300" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>

                </div>

                {{-- CONTENIDO --}}
                <div class="p-8 space-y-10 text-gray-800">

                    {{-- SECCIÓN 1: DATOS GENERALES --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Datos Personales
                        </h4>

                        <p><strong>Fecha de nacimiento:</strong>
                            {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}
                        </p>

                        <p><strong>Nº Historia / NUHSA:</strong> {{ $paciente->nuhsa }}</p>

                        <p><strong>Sexo:</strong> {{ $paciente->sexo }}</p>
                    </div>


                    {{-- SECCIÓN 2: DATOS SOMATOMÉTRICOS --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Datos Somatométricos
                        </h4>

                        @php
                            $imc = null;
                            if ($paciente->peso && $paciente->altura) {
                                $imc = round($paciente->peso / (($paciente->altura / 100) ** 2), 1);
                            }
                        @endphp

                        <p><strong>Peso:</strong> {{ $paciente->peso }} kg</p>
                        <p><strong>Altura:</strong> {{ $paciente->altura }} cm</p>

                        <p>
                            <strong>IMC:</strong>

                            @if($imc)
                                <span class="
                                    px-3 py-1 rounded-full text-sm
                                    @if($imc < 25) bg-green-100 text-green-700
                                    @elseif($imc < 30) bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700
                                    @endif
                                ">
                                    {{ $imc }}
                                </span>
                            @else
                                <span class="text-gray-500">No disponible</span>
                            @endif
                        </p>
                    </div>


                    {{-- SECCIÓN 3: INFORMACIÓN CLÍNICA RELACIONADA --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Información Clínica Asociada
                        </h4>

                        <ul class="list-disc ml-6 space-y-2">

                            <li>
                                <a href="{{ route('trasplantes.index') }}?paciente_id={{ $paciente->id }}"
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver trasplantes del paciente →
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('diagnosticos.index') }}?paciente_id={{ $paciente->id }}"
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver diagnósticos →
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('pruebas.index') }}?paciente_id={{ $paciente->id }}"
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver pruebas clínicas →
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('sintomas.index') }}?paciente_id={{ $paciente->id }}"
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver síntomas →
                                </a>
                            </li>

                        </ul>
                    </div>


                    {{-- SECCIÓN 4: BOTONES DE ACCIÓN CLÍNICA --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Acciones Clínicas Rápidas
                        </h4>

                        <div class="flex flex-wrap gap-4">

                            <a href="{{ route('diagnosticos.create') }}?paciente_id={{ $paciente->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar diagnóstico
                            </a>

                            <a href="{{ route('trasplantes.create') }}?paciente_id={{ $paciente->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar trasplante
                            </a>

                            <a href="{{ route('pruebas.create') }}?paciente_id={{ $paciente->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar prueba clínica
                            </a>

                            <a href="#"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                + Registrar síntoma
                            </a>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>
