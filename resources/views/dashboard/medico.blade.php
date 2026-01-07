<x-medico-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel Médico
        </h3>
    </x-slot>

    {{-- KPIs informativos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Pacientes</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['pacientes'] ?? 0 }}</div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Diagnósticos</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['diagnosticos'] ?? 0 }}</div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Tratamientos</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['tratamientos'] ?? 0 }}</div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-500">Pruebas clínicas</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $stats['pruebas'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Acciones rápidas (workflow) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- Buscar paciente (entrada natural) --}}
        <div class="bg-white shadow-md rounded-lg p-6 border border-blue-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Buscar paciente</h3>
                    <p class="text-sm text-gray-600">
                        Entrar en ficha clínica para inferir, revisar y registrar evolución.
                    </p>
                </div>
                <i class="fas fa-search text-blue-600 text-2xl"></i>
            </div>

            <form method="GET" action="{{ route('pacientes.index') }}" class="mt-4 flex gap-3">
                <input type="text"
                       name="nombre"
                       class="w-full border border-gray-300 rounded-md p-2"
                       placeholder="Buscar por nombre / NUHSA">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                    Buscar
                </button>
            </form>

            <div class="mt-3 text-xs text-gray-500">
                Recomendación: trabajar desde la ficha del paciente como pantalla principal.
            </div>
        </div>

        {{-- Inferir diagnóstico (atajo) --}}
        <a href="{{ route('diagnosticos.inferirSelector') }}"
           class="bg-white shadow-md rounded-lg p-6 border border-purple-200 hover:border-purple-400 hover:bg-purple-50 transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Inferir diagnóstico</h3>
                    <p class="text-sm text-gray-600">
                        Atajo para lanzar inferencia sin entrar primero en la ficha.
                    </p>
                </div>
                <i class="fas fa-diagnoses text-purple-600 text-2xl"></i>
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
