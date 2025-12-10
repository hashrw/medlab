<aside class="sidebar w-64 bg-white shadow-md h-screen">
    <nav class="py-4 flex flex-col justify-between h-full">
        <div>
            <div class="mb-6 px-4">
                <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-4">
                    Menú principal
                </h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard.medico') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('dashboard.medico') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-home w-5 mr-2"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pacientes.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('pacientes.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-user-injured w-5 mr-2"></i>
                            <span>Módulo de Pacientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('diagnosticos.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('diagnosticos.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-file-medical w-5 mr-2"></i>
                            <span>Módulo de Diagnósticos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tratamientos.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('tratamientos.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-pills w-5 mr-2"></i>
                            <span>Módulo de Tratamientos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medicamentos.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('medicamentos.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-prescription-bottle-alt w-5 mr-2"></i>
                            <span>Módulo de Medicamentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('trasplantes.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('trasplantes.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-calendar-check w-5 mr-2"></i>
                            <span>Módulo de Trasplantes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pruebas.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('pruebas.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-vials w-5 mr-2"></i>
                            <span>Módulo de Pruebas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sintomas.index') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('sintomas.*') ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}">
                            <i class="fas fa-heartbeat w-5 mr-2"></i>
                            <span>Módulo de Síntomas</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="px-4 py-3 border-t border-gray-200">
            <div class="text-sm text-gray-600 flex items-center">
                <i class="fas fa-user-md mr-2 text-blue-500"></i>
                <span>{{ Auth::user()->name ?? 'Médico' }}</span>
            </div>
        </div>
    </nav>
</aside>