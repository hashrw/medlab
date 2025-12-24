<x-medico-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Médico') }}
        </h3>
    </x-slot>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Pacientes</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['pacientes'] ?? 0 }}</div>
            <a class="text-sm text-blue-700 hover:underline" href="{{ route('pacientes.index') }}">Ver listado</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Diagnósticos</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['diagnosticos'] ?? 0 }}</div>
            <a class="text-sm text-blue-700 hover:underline" href="{{ route('diagnosticos.index') }}">Ver listado</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Tratamientos</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['tratamientos'] ?? 0 }}</div>
            <a class="text-sm text-blue-700 hover:underline" href="{{ route('tratamientos.index') }}">Ver listado</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Pruebas clínicas</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['pruebas'] ?? 0 }}</div>
            <a class="text-sm text-blue-700 hover:underline" href="{{ route('pruebas.index') }}">Ver listado</a>
        </div>
    </div>

    {{-- Tus tarjetas (sin cambios) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">

        <a href="{{ route('diagnosticos.inferirSelector') }}"
            class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-diagnoses text-blue-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Inferir diagnósticos</h3>
                    <p class="text-sm text-gray-600">Elegir paciente y ejecutar inferencia clínica.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('estadisticas.index') }}"
            class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-chart-line text-green-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Evolución y métricas clínicas</h3>
                    <p class="text-sm text-gray-600">EICH: actividad por órgano, diagnósticos, tratamientos y pruebas.
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('tratamientos.create') }}"
            class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-pills text-purple-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Registrar tratamiento</h3>
                    <p class="text-sm text-gray-600">Crear y asociar un tratamiento a un paciente.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('pacientes.create') }}"
            class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-user-plus text-indigo-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Registrar nuevo paciente</h3>
                    <p class="text-sm text-gray-600">Crear ficha clínica de un paciente.</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Últimos registros --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 font-semibold text-gray-800">
                Últimos pacientes
            </div>
            <div class="p-4">
                @forelse(($ultimos['pacientes'] ?? []) as $p)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                        <div class="text-sm text-gray-800">
                            {{ $p->nombre ?? ('Paciente #' . $p->id) }}
                        </div>
                        <a class="text-sm text-blue-700 hover:underline" href="{{ route('pacientes.show', $p) }}">
                            Abrir
                        </a>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Sin registros.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 font-semibold text-gray-800">
                Últimos diagnósticos
            </div>
            <div class="p-4">
                @forelse(($ultimos['diagnosticos'] ?? []) as $d)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                        <div class="text-sm text-gray-800">
                            {{ $d->nombre ?? ('Diagnóstico #' . $d->id) }}
                        </div>
                        <a class="text-sm text-blue-700 hover:underline" href="{{ route('diagnosticos.show', $d) }}">
                            Abrir
                        </a>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Sin registros.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-medico-layout>