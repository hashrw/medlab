<aside class="sidebar w-64 bg-white shadow-md h-screen">
    @php
        $enInicio = request()->routeIs('dashboard.medico');

        $enFlujoPaciente =
            request()->routeIs('pacientes.*')
            || request()->routeIs('pacientes.historiaClinica')
            || request()->routeIs('diagnosticos.inferirSelector')
            || request()->routeIs('diagnosticos.inferir')
            || request()->routeIs('tratamientos.inferirDesdeDiagnostico');

        $enFlujoCitas = request()->routeIs('citas.*');
    @endphp

    <nav class="py-4 flex flex-col justify-between h-full">
        <div>
            <div class="mb-6 px-4">
                <div class="mb-4">
                    <div class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold">
                        Entorno clínico
                    </div>
                    <div class="text-sm text-gray-700 font-semibold">
                        Investigación EICH
                    </div>
                </div>

                {{-- INICIO (SIEMPRE VISIBLE) --}}
                <ul class="space-y-1 mb-6">
                    <li>
                        <a href="{{ route('dashboard.medico') }}"
                            class="sidebar-link flex items-center px-3 py-2 rounded-md
                           {{ $enInicio ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                            <i class="fas fa-home w-5 mr-2"></i>
                            <span>Inicio clínico</span>
                        </a>
                    </li>
                </ul>

                {{-- ACCIÓN CLÍNICA (SOLO SI HAY CONTEXTO ACTIVO) --}}
                @if($enFlujoPaciente || $enFlujoCitas)
                    <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-3">
                        Acción clínica
                    </h3>

                    <ul class="space-y-1 mb-8">
                        @if($enFlujoPaciente)
                            <li>
                                <div class="flex items-center px-3 py-2 rounded-md bg-blue-100 text-blue-600 font-semibold">
                                    <i class="fas fa-stethoscope w-5 mr-2"></i>
                                    <span>Buscar paciente (motor e inferencia)</span>
                                </div>
                            </li>
                        @endif

                        @if($enFlujoCitas)
                            <li>
                                <div class="flex items-center px-3 py-2 rounded-md bg-blue-100 text-blue-600 font-semibold">
                                    <i class="fas fa-calendar-alt w-5 mr-2"></i>
                                    <span>Gestión de citas</span>
                                </div>
                            </li>
                        @endif
                    </ul>
                @endif

                {{-- DATOS CLÍNICOS Y SEGUIMIENTO --}}
                <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-3">
                    Datos clínicos y seguimiento
                </h3>

                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('estadisticas.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition">
                            <i class="fas fa-chart-line w-5 mr-2"></i>
                            <span>Estadísticas clínicas</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- <div class="px-4 py-3 border-t border-gray-200">
            <div class="text-sm text-gray-600 flex items-center">
                <i class="fas fa-user-md mr-2 text-blue-500"></i>
                <span>{{ Auth::user()->name ?? 'Médico' }}</span>
            </div>
        </div>--}}
    </nav>
</aside>