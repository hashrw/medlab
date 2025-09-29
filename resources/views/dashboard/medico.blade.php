<x-medico-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Médico') }}
        </h3>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">

        <!-- Tarjeta 1: Generar Diagnóstico -->
        <a href="{{ route('diagnosticos.create') }}"
           class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-diagnoses text-blue-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Generar Diagnóstico</h3>
                    <p class="text-sm text-gray-600">Crear nuevo diagnóstico o ejecutar inferencia clínica.</p>
                </div>
            </div>
        </a>

        <!-- Tarjeta 2: Estadísticas y evolución -->
        <a href="{{ route('estadisticas.index') }}"
           class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-chart-line text-green-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Estadísticas y evolución</h3>
                    <p class="text-sm text-gray-600">Ver evolución clínica de los pacientes.</p>
                </div>
            </div>
        </a>

        <!-- Tarjeta 3: Generar Tratamiento -->
        <a href="{{ route('tratamientos.create') }}"
           class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-pills text-purple-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Generar Tratamiento</h3>
                    <p class="text-sm text-gray-600">Asignar tratamientos a pacientes.</p>
                </div>
            </div>
        </a>

        <!-- Tarjeta 4: Reglas clínicas -->
        <a href="{{ route('reglas.index') }}"
           class="bg-white shadow-md rounded-lg p-6 border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition">
            <div class="flex items-center space-x-4">
                <i class="fas fa-project-diagram text-red-600 text-3xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Reglas clínicas</h3>
                    <p class="text-sm text-gray-600">Gestionar reglas clínicas basadas en síntomas y diagnósticos.</p>
                </div>
            </div>
        </a>

    </div>
</x-medico-layout>
