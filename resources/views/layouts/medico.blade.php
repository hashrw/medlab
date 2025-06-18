<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-blue-800 text-white w-64 p-4 space-y-4">
        <h2 class="text-2xl font-bold mb-6">EICHsys</h2>
        <ul class="space-y-2">
            <li><a href="{{ route('dashboard.medico') }}" class="block p-2 hover:bg-blue-700 rounded">🏠 Dashboard
                    Médico</a></li>
            <li><a href="{{ route('pacientes.index') }}" class="block p-2 hover:bg-blue-700 rounded">👥 Pacientes</a>
            </li>
            <li><a href="{{ route('diagnosticos.index') }}" class="block p-2 hover:bg-blue-700 rounded">📝
                    Diagnósticos</a></li>
            <li><a href="{{ route('sintomas.index') }}" class="block p-2 hover:bg-blue-700 rounded">🩺 Síntomas</a></li>
            <li><a href="{{ route('tratamientos.index') }}" class="block p-2 hover:bg-blue-700 rounded">💊
                    Tratamientos</a></li>

            <li>
                <form method=\"POST\" action=\"{{ route('logout') }}\">
                    @csrf
                    <button class=\"w-full text-left p-2 hover:bg-blue-700 rounded\">⬅️ Salir</button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8 bg-gray-100">
        <h1 class="text-2xl font-bold mb-6">
            {{ $titulo ?? $header ?? 'Panel Médico' }}
        </h1>

    </main>
</div>