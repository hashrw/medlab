<x-medico-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Estadísticas clínicas
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Diagnósticos</p>
            <p class="text-2xl font-bold">{{ $totalDiagnosticos }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Informes clínicos</p>
            <p class="text-2xl font-bold">{{ $informesTotales }}</p>
            <p class="text-xs text-gray-400">Fallback: {{ $informesFallback }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Tratamientos</p>
            <p class="text-2xl font-bold">{{ $tratamientosTotales }}</p>
        </div>

    </div>
</x-medico-layout>