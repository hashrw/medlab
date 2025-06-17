<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Médico') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Contenido del Dashboard -->
                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="bg-blue-800 text-white w-64 p-4">
                            <h2 class="text-xl font-bold mb-4">Panel Médico</h2>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('pacientes.index') }}" class="block p-2 hover:bg-blue-700 rounded">
                                        <i class="fas fa-users mr-2"></i>Pacientes
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('diagnosticos.index') }}" class="block p-2 hover:bg-blue-700 rounded">
                                        <i class="fas fa-stethoscope mr-2"></i>Diagnósticos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tratamientos.index') }}" class="block p-2 hover:bg-blue-700 rounded">
                                        <i class="fas fa-pills mr-2"></i>Tratamientos
                                    </a>
                                </li>
                                <li>
                                    
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <main class="flex-1 p-6 bg-gray-100">
                            <h1 class="text-2xl font-bold mb-6">Bienvenido, {{ Auth::user()->name }}</h1>

                            <!-- Bloque de Tarjetas (2 por fila) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Tarjeta de Pacientes -->
                                <div class="bg-white p-6 rounded-lg shadow-md">
                                    <h2 class="text-xl font-bold mb-4">Pacientes</h2>
                                     <!--<p class="text-gray-700">Total de pacientes: <span class="font-bold">120</span></p>-->
                                    <a href="{{ route('pacientes.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Ver todos
                                    </a>
                                </div>

                                <!-- Tarjeta de Diagnósticos -->
                                <div class="bg-white p-6 rounded-lg shadow-md">
                                    <h2 class="text-xl font-bold mb-4">Diagnósticos</h2>
                                    <!--<p class="text-gray-700">Diagnósticos realizados: <span class="font-bold">45</span></p> -->
                                    <a href="{{ route('diagnosticos.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Ver todos
                                    </a>
                                </div>

                                <!-- Tarjeta de Tratamientos -->
                                <div class="bg-white p-6 rounded-lg shadow-md">
                                    <h2 class="text-xl font-bold mb-4">Tratamientos</h2>
                                     <!--<p class="text-gray-700">Tratamientos activos: <span class="font-bold">30</span></p>-->
                                    <a href="{{ route('tratamientos.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Ver todos
                                    </a>
                                </div>

                                <!-- Tarjeta de Estadísticas -->
                                <div class="bg-white p-6 rounded-lg shadow-md">
                                    <h2 class="text-xl font-bold mb-4">Registro de medicamentos</h2>
                                     <!--<div class="space-y-2">
                                        <p class="text-gray-700">Pacientes atendidos: <span class="font-bold">120</span></p>
                                        <p class="text-gray-700">Diagnósticos comunes: <span class="font-bold">Gripe (25 casos)</span></p>
                                        <p class="text-gray-700">Tratamientos activos: <span class="font-bold">30</span></p>
                                    </div>-->
                                    <a href="{{ route('medicamentos.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Ver todos
                                    </a>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>