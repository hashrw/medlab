<x-paciente-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Mi ficha clínica
                </h2>

                @if(isset($paciente) && $paciente)
                    <p class="text-sm text-gray-500 mt-1">
                        NUHSA: <span class="font-semibold text-gray-700">{{ $paciente->nuhsa }}</span>
                        @if($paciente->fecha_nacimiento)
                            <span class="mx-2 text-gray-300">|</span>
                            Edad: <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }}</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    @php
        $tab = request('tab', 'datos'); // datos | cita
    @endphp

    @if(!isset($paciente) || !$paciente)
        <div class="border border-red-200 bg-red-50 text-red-800 rounded-lg p-4 text-sm">
            Tu usuario no tiene un paciente clínico asociado. Contacta con soporte.
        </div>
    @else
        {{-- Tabs --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                <a href="{{ route('dashboard.paciente', ['tab' => 'datos']) }}"
                   class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                   {{ $tab === 'datos'
                        ? 'border-blue-600 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                   }}">
                    Consulta de mis datos
                </a>

                <a href="{{ route('dashboard.paciente', ['tab' => 'cita']) }}"
                   class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                   {{ $tab === 'cita'
                        ? 'border-blue-600 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                   }}">
                    Solicitar cita
                </a>
            </nav>
        </div>

        {{-- Contenido --}}
        @if($tab === 'cita')
            @include('dashboard.paciente.partials.cita', ['paciente' => $paciente])
        @else
            @include('dashboard.paciente.partials.datos', ['paciente' => $paciente])
        @endif
    @endif
</x-paciente-layout>
