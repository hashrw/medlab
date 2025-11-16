<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ficha del Trasplante
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-start">

                    <div>
                        <h3 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-dna"></i>
                            {{ $trasplante->tipo_trasplante }}
                        </h3>

                        <p class="text-blue-100 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Fecha del trasplante:
                            <span class="font-semibold">
                                {{ $trasplante->fecha_trasplante->format('d/m/Y') }}
                            </span>
                        </p>

                        <p class="text-blue-100 mt-1 flex items-center gap-2">
                            <i class="fas fa-shield-virus"></i>
                            Compatibilidad HLA:
                            <span class="font-semibold">{{ $trasplante->identidad_hla }}</span>
                        </p>

                    </div>

                    {{-- Acciones --}}
                    <div class="flex space-x-4 text-lg">

                        <a href="{{ route('trasplantes.index') }}" class="hover:text-gray-200" title="Volver">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <a href="{{ route('trasplantes.edit', $trasplante->id) }}" class="hover:text-yellow-300"
                            title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('trasplantes.destroy', $trasplante->id) }}"
                            onsubmit="return confirm('¿Eliminar este trasplante?')">
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

                    {{-- SECCIÓN PACIENTE --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Paciente
                        </h4>

                        <div class="flex items-center gap-4">

                            {{-- Foto / Inicial --}}
                            @if($trasplante->paciente && $trasplante->paciente->avatar)
                                <img src="{{ asset('storage/' . $trasplante->paciente->avatar) }}"
                                    class="w-14 h-14 rounded-full object-cover">
                            @else
                                <div class="w-14 h-14 rounded-full bg-blue-200 flex items-center
                                                    justify-center text-blue-800 font-bold text-xl">
                                    {{ strtoupper(substr($trasplante->paciente->nombre, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <a href="{{ route('pacientes.show', $trasplante->paciente_id) }}"
                                    class="text-blue-700 text-lg font-semibold hover:text-blue-900">
                                    {{ $trasplante->paciente->nombre }}
                                </a>

                                <p class="text-gray-600">
                                    NUHSA: {{ $trasplante->paciente->nuhsa }}
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- SECCIÓN DATOS PRINCIPALES --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Datos principales del trasplante
                        </h4>

                        <p><strong>Tipo:</strong> {{ $trasplante->tipo_trasplante }}</p>
                        <p><strong>Fecha:</strong> {{ $trasplante->fecha_trasplante->format('d/m/Y') }}</p>
                        <p><strong>Origen del injerto:</strong> {{ $trasplante->origen_trasplante }}</p>
                        <p><strong>Compatibilidad HLA:</strong> {{ $trasplante->identidad_hla }}</p>
                    </div>

                    {{-- SECCIÓN ACONDICIONAMIENTO --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Acondicionamiento
                        </h4>

                        <p><strong>Tipo de acondicionamiento:</strong> {{ $trasplante->tipo_acondicionamiento }}</p>
                    </div>

                    {{-- SECCIÓN SEROLOGÍA --}}
                    {{-- 3. SEROLOGÍAS --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-2 border-b pb-1">
                            Seropositividad
                        </h4>

                        <div class="flex space-x-4">

                            {{-- Donante --}}
                            <div>
                                <p class="font-semibold">Donante:</p>
                                <span class="px-3 py-1 rounded-full text-sm
                                    {{ $trasplante->seropositividad_donante == 'Negativo'
    ? 'bg-green-100 text-green-700'
    : 'bg-red-100 text-red-700' }}">
                                    {{ $trasplante->seropositividad_donante }}
                                </span>
                            </div>

                            {{-- Receptor --}}
                            <div>
                                <p class="font-semibold">Receptor:</p>
                                <span class="px-3 py-1 rounded-full text-sm
                                    {{ $trasplante->seropositividad_receptor == 'Negativo'
    ? 'bg-green-100 text-green-700'
    : 'bg-red-100 text-red-700' }}">
                                    {{ $trasplante->seropositividad_receptor }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- ACCIONES CLÍNICAS --}}
                    <div>
                        <h4 class="text-lg font-semibold text-blue-700 mb-3 border-b pb-1">
                            Acciones clínicas
                        </h4>

                        <div class="flex flex-wrap gap-4">

                            <a href="{{ route('pruebas.index') }}?paciente_id={{ $trasplante->paciente_id }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Ver pruebas clínicas →
                            </a>

                            <a href="{{ route('diagnosticos.index') }}?paciente_id={{ $trasplante->paciente_id }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Ver diagnósticos →
                            </a>

                            <a href="{{ route('sintomas.index') }}?paciente_id={{ $trasplante->paciente_id }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Ver síntomas →
                            </a>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-medico-layout>