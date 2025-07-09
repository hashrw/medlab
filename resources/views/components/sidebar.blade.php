<div class="w-64 bg-white shadow-md fixed h-full">
    <div class="p-4 border-b">
        <h2 class="text-xl font-bold">{{ config('app.name') }}</h2>
    </div>
    <nav class="p-4">
        <div class="mb-6">
            <h3 class="text-sm uppercase text-gray-500 font-semibold mb-2">Menú Principal</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('pacientes.index') }}" class="block px-4 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('pacientes.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-user-injured mr-2"></i> Pacientes
                    </a>
                </li>
                <li>
                    <a href="{{ route('diagnosticos.index') }}" class="block px-4 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('diagnosticos.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-file-medical mr-2"></i> Diagnósticos
                    </a>
                </li>
                <li>
                    <a href="{{ route('tratamientos.index') }}" class="block px-4 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('tratamientos.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-pills mr-2"></i> Tratamiento
                    </a>
                </li>
                <li>
                    <a href="{{ route('medicamentos.index') }}" class="block px-4 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('medicamentos.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-prescription-bottle-alt mr-2"></i> Medicamentos
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>