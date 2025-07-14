<!-- resources/views/layouts/components/sidebar-medico.blade.php -->

<aside class="sidebar">
    <nav class="py-4">
        <div class="mb-6 px-4">
            <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-4">Menú principal</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard.medico') }}"
                        class="sidebar-link {{ request()->routeIs('dashboard.medico') ? 'active' : '' }}">
                        <i class="fas fa-home sidebar-icon"></i>
                        <span class="sidebar-text">Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pacientes.index') }}"
                        class="sidebar-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
                        <i class="fas fa-user-injured sidebar-icon"></i>
                        <span class="sidebar-text">Módulo de pacientes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('diagnosticos.index') }}"
                        class="sidebar-link {{ request()->routeIs('diagnosticos.*') ? 'active' : '' }}">
                        <i class="fas fa-file-medical sidebar-icon"></i>
                        <span class="sidebar-text">Módulo de diagnósticos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tratamientos.index') }}"
                        class="sidebar-link {{ request()->routeIs('tratamientos.*') ? 'active' : '' }}">
                        <i class="fas fa-pills sidebar-icon"></i>
                        <span class="sidebar-text">Módulo de tratamientos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('medicamentos.index') }}"
                        class="sidebar-link {{ request()->routeIs('medicamentos.*') ? 'active' : '' }}">
                        <i class="fas fa-prescription-bottle-alt sidebar-icon"></i>
                        <span class="sidebar-text">Módulo de medicamentos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('citas.index') }}"
                        class="sidebar-link {{ request()->routeIs('citas.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check sidebar-icon"></i>
                        <span class="sidebar-text">Módulo de Citas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pruebas.index') }}"
                        class="sidebar-link {{ request()->routeIs('pruebas.*') ? 'active' : '' }}">
                        <i class="fas fa-vials sidebar-icon"></i>
                        <span class="sidebar-text">Módulo de Pruebas</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="px-4 py-3 border-t border-gray-100 mt-4">
            <div class="text-sm text-gray-600 flex items-center">
                <i class="fas fa-user-md mr-2 text-blue-500"></i>
                <span>Médico 1</span>
            </div>
        </div>
    </nav>
</aside>