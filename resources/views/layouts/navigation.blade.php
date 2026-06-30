<nav x-data="{ open: false }" class="bg-slate-50 border-b border-slate-200 shadow-sm">
    <div class="max-w-[1200px] mx-auto px-6 lg:px-12">
        <div class="flex items-center justify-between h-16">

            @php
                $user = Auth::user();

                $dashboardRoute = match (true) {
                    $user?->tipo_usuario_id === 1 => route('dashboard.medico'),
                    $user?->tipo_usuario_id === 2 => route('dashboard.paciente'),
                    default => route('dashboard'),
                };
            @endphp

            <div class="flex items-center h-full">
                <a href="{{ $dashboardRoute }}"
                    class="flex items-center h-12 px-4 bg-white border border-slate-200 rounded-md shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Sistema clínico') }}"
                        class="h-9 w-auto">
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 text-xs text-slate-500">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" title="Cerrar sesión" class="inline-flex items-center justify-center w-10 h-10 rounded-md
       bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm">
                        <i class="fas fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>