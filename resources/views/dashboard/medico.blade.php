<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6">Bienvenido, {{ Auth::user()->name }}</h1>
                    
                    <!-- Espacio reservado para futuros accesos directos -->
                    <div class="text-center py-12">
                        <i class="fas fa-clinic-medical text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Panel de control médico</p>
                        <p class="text-sm text-gray-400 mt-2">Aquí se mostrarán los accesos directos y estadísticas importantes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>