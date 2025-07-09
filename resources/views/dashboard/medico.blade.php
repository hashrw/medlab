<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #2b6cb0;
            --secondary-color: #4a5568;
            --highlight-color: #ebf8ff;
            --sidebar-bg: #f7fafc;
        }

        /* Estructura principal */
        .app-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Barra de navegación superior */
        .nav-container {
            width: 100%;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            z-index: 50;
            position: relative;
        }

        .nav-content {
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
        }

        /* Contenedor principal (sidebar + content) */
        .main-container {
            display: flex;
            flex: 1;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            height: calc(100vh - 64px);
            /* Restar altura de la barra de navegación */
            overflow-y: auto;
            z-index: 30;
        }


        /* Contenido principal */
        .content {
            flex: 1;
            min-height: calc(100vh - 64px);
            position: relative;
            z-index: 20;
        }

        /* Estilos del menú */
        .menu-item {
            display: block;
            padding: 12px 16px;
            color: var(--secondary-color);
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background-color: #e2e8f0;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .menu-item.active {
            background-color: var(--highlight-color);
            color: var(--primary-color);
            font-weight: 500;
            border-left-color: var(--primary-color);
        }

        .menu-icon {
            color: #718096;
            width: 20px;
            text-align: center;
            margin-right: 12px;
            transition: color 0.2s ease;
        }

        .menu-item:hover .menu-icon,
        .menu-item.active .menu-icon {
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 64px;
                height: calc(100vh - 64px);
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 40;
            }

            .sidebar-open .sidebar {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 35;
            }

            .sidebar-open .overlay {
                display: block;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="app-wrapper">
        <!-- Barra de navegación superior -->
        <div class="nav-container max-w-[1440px] mx-auto">
            <div class="nav-content">
                @include('layouts.navigation')
            </div>
        </div>

        <!-- Contenedor principal -->
        <div class="main-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="py-4">
                    <div class="mb-6 px-4">
                        <h3 class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-4">MENÚ PRINCIPAL
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('dashboard.medico') }}"
                                    class="menu-item {{ request()->routeIs('dashboard.medico') ? 'active' : '' }}">
                                    <i class="fas fa-home menu-icon"></i>
                                    <span>Inicio</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pacientes.index') }}"
                                    class="menu-item {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-injured menu-icon"></i>
                                    <span>Módulo de pacientes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('diagnosticos.index') }}"
                                    class="menu-item {{ request()->routeIs('diagnosticos.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-medical menu-icon"></i>
                                    <span>Módulo de diagnósticos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tratamientos.index') }}"
                                    class="menu-item {{ request()->routeIs('tratamientos.*') ? 'active' : '' }}">
                                    <i class="fas fa-pills menu-icon"></i>
                                    <span>Módulo de tratamientos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('medicamentos.index') }}"
                                    class="menu-item {{ request()->routeIs('medicamentos.*') ? 'active' : '' }}">
                                    <i class="fas fa-prescription-bottle-alt menu-icon"></i>
                                    <span>Módulo de medicamentos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('citas.index') }}"
                                    class="menu-item {{ request()->routeIs('citas.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check menu-icon"></i>
                                    <span>Módulo de Citas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pruebas.index') }}"
                                    class="menu-item {{ request()->routeIs('pruebas.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check menu-icon"></i>
                                    <span>Módulo de Pruebas </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200 mt-4">
                        <div class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span>Médico 1</span>
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Overlay para móviles -->
            <div class="overlay" id="overlay"></div>

            <!-- Contenido principal -->
            <main class="content">
                @if (isset($header))
                    <header class="border-b border-gray-200 bg-white">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Mensajes flash -->
                @if (session()->has('warning'))
                    <div class="mx-4 mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="mx-4 mt-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                
            </main>
        </div>
    </div>

    <!-- Botón para móvil -->
    <button id="menu-toggle"
        class="md:hidden fixed bottom-6 right-6 bg-blue-500 text-white p-3 rounded-full shadow-lg z-50">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        // Toggle sidebar en móviles
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });

        // Cerrar sidebar al hacer clic en el overlay
        document.getElementById('overlay').addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    </script>
</body>

</html>