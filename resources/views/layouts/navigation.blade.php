<nav x-data="{ open: false }" class="px-4 sm:px-6 lg:px-8 py-3 bg-white shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @php
                        $user = Auth::user();
                        $dashboardRoute = match (true) {
                            $user?->tipo_usuario_id === 1 => route('dashboard.medico'),
                            $user?->tipo_usuario_id === 2 => route('dashboard.paciente'),
                            default => route('dashboard'),
                        };
                    @endphp

                    <a href="{{ $dashboardRoute }}">
                        <x-application-logo class="h-15 w-auto mr-2" />
                    </a>
                </div>

                <!-- Navigation Links
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->es_administrador || Auth::user()->es_paciente || Auth::user()->es_medico)
                        <!--{{ <x-nav-link :href="route('citas.index')" :active="request()->routeIs('citas.index') or request()->routeIs('citas.create') or request()->routeIs('citas.edit') or request()->routeIs('citas.show')">
                                {{ __('Mis citas') }} }}
                            </x-nav-link>
                        <x-nav-link :href="route('diagnosticos.index')" :active="request()->routeIs('diagnosticos.index') or request()->routeIs('diagnosticos.create') or request()->routeIs('diagnosticos.edit') or request()->routeIs('diagnosticos.show')">
                            {{ __('Diagnósticos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('sintomas.index')" :active="request()->routeIs('sintomas.index') or request()->routeIs('sintomas.create') or request()->routeIs('sintomas.edit') or request()->routeIs('sintomas.show')">
                            {{ __('Síntomas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('medicamentos.index')" :active="request()->routeIs('medicamentos.index') or request()->routeIs('medicamentos.create') or request()->routeIs('medicamentos.edit') or request()->routeIs('medicamentos.show')">
                            {{ __('Medicamentos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('organos.index')" :active="request()->routeIs('organos.index') or request()->routeIs('organos.create') or request()->routeIs('organos.edit') or request()->routeIs('organos.show')">
                            {{ __('Órganos') }}
                        </x-nav-link>

                    @endif
                    @if(Auth::user()->es_administrador)
                        <x-nav-link :href="route('medicamentos.index')" :active="request()->routeIs('medicamentos.index') or request()->routeIs('medicamentos.create') or request()->routeIs('medicamentos.edit') or request()->routeIs('medicamentos.show')">
                            {{ __('Medicamentos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('especialidads.index')" :active="request()->routeIs('especialidads.index') or request()->routeIs('especialidads.create') or request()->routeIs('especialidads.edit')">
                            {{ __('Especialidades') }}
                        </x-nav-link>
                        <x-nav-link :href="route('medicos.index')" :active="request()->routeIs('medicos.index') or request()->routeIs('medicos.create') or request()->routeIs('medicos.edit') or request()->routeIs('medicos.show')">
                            {{ __('Médicos') }}
                        </x-nav-link>
                        <x-nav-link :href="route('citas.index')" :active="request()->routeIs('citas.index') or request()->routeIs('citas.create') or request()->routeIs('citas.edit') or request()->routeIs('citas.show')">
                            {{ __('Citas') }}
                        </x-nav-link>
                    @endif
                </div>-->
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Auth::user()->es_medico)
                            <x-dropdown-link :href="route('medicos.edit', Auth::user()->medico->id)">
                                {{ __('Mi perfil') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Salir') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>