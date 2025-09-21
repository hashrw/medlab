<x-medico-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Médico') }}
        </h3>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-1">
    <!-- Tarjeta 1: Generar Diagnóstico -->
    <a href="{{ route('diagnosticos.create') }}" class="bg-white shadow-md rounded-lg p-6 hover:bg-blue-50 border border-blue-300 transition">
        <div class="flex items-center space-x-4">
            <i class="fas fa-diagnoses text-blue-600 text-3xl"></i>
            <div>
                <h3 class="text-lg font-semibold">Generar Diagnóstico</h3>
                <p class="text-sm text-gray-600">Crear nuevo diagnóstico o ejecutar inferencia clínica.</p>
            </div>
        </div>
    </a>

    <!-- Tarjeta 2: Estadísticas -->
    <a href="{{ route('estadisticas.index') }}" class="bg-white shadow-md rounded-lg p-6 hover:bg-blue-50 border border-blue-300 transition">
        <div class="flex items-center space-x-4">
            <i class="fas fa-chart-line text-green-600 text-3xl"></i>
            <div>
                <h3 class="text-lg font-semibold">Estadísticas y evolución</h3>
                <p class="text-sm text-gray-600">Ver evolución clínica de los pacientes.</p>
            </div>
        </div>
    </a>

    <!-- Tarjeta 3: Tratamientos -->
    <a href="{{ route('tratamientos.create') }}" class="bg-white shadow-md rounded-lg p-6 hover:bg-blue-50 border border-blue-300 transition">
        <div class="flex items-center space-x-4">
            <i class="fas fa-pills text-purple-600 text-3xl"></i>
            <div>
                <h3 class="text-lg font-semibold">Generar Tratamiento</h3>
                <p class="text-sm text-gray-600">Asignar tratamientos a pacientes.</p>
            </div>
        </div>
    </a>

    <!-- Tarjeta 4: Reglas clínicas -->
    <a href="{{ route('reglas.index') }}" class="bg-white shadow-md rounded-lg p-6 hover:bg-blue-50 border border-blue-300 transition">
        <div class="flex items-center space-x-4">
            <i class="fas fa-project-diagram text-red-600 text-3xl"></i>
            <div>
                <h3 class="text-lg font-semibold">Reglas clínicas</h3>
                <p class="text-sm text-gray-600">Gestionar reglas clínicas basadas en síntomas y diagnósticos.</p>
            </div>
        </div>
    </a>
</div>

</x-medico-layout>
