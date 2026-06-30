<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Médico</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Estilos propios -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/medico.css') }}">
    @stack('styles')

</head>

<!-- En x-medico-layout -->

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        @include('layouts.navigation')

        <!-- Main layout content -->
        <div class="flex-grow flex justify-center">
            {{-- 🔽 CAMBIO: antes era "w-full max-w-[1440px] px-6 py-4 flex"
            ahora centramos y dejamos márgenes ajustables con mx-auto y max-w --}}
            <div class="w-full max-w-[1200px] flex mx-auto px-6 lg:px-12 py-6">
                <!-- Sidebar -->
                <aside class="w-[240px] shrink-0">
                    @include('components.sidebar-medico')
                </aside>

                <!-- Contenido -->
                <main class="flex-1 ml-6">
                    @if (isset($header))
                        <header class="border-b border-gray-200 bg-white mb-4">
                            <div class="py-4 px-6">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Flash Messages 
                     <x-flash-message type="success" />
                     <x-flash-message type="warning" />
                     <x-flash-message type="error" /> -->

                    <div class="bg-white shadow rounded p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
    <div id="clinical-report-toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    <script>
        function showClinicalToast(message, url) {
            const container = document.getElementById('clinical-report-toast-container');

            if (!container) return;

            const toast = document.createElement('div');

            toast.className = "bg-white border border-blue-200 shadow-lg rounded px-4 py-3 text-sm text-gray-800 flex items-center gap-3";

            toast.innerHTML = `
            <div class="flex-1">
                <p class="font-semibold text-blue-900">${message}</p>
                <p class="text-xs text-gray-500">Disponible para revisión</p>
            </div>
            <a href="${url}"
               class="text-blue-600 hover:underline text-xs">
               Abrir
            </a>
        `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 8000);
        }

        document.addEventListener('DOMContentLoaded', function () {

            setInterval(async function () {
                try {
                    const response = await fetch("{{ route('informes-clinicos.notificaciones') }}");

                    if (!response.ok) return;

                    const data = await response.json();

                    if (!data.items || !data.items.length) return;

                    const currentPath = window.location.pathname;

                    data.items.forEach(item => {
                        if (item.url) {
                            const itemPath = new URL(item.url, window.location.origin).pathname;

                            if (currentPath === itemPath) {
                                return;
                            }
                        }

                        showClinicalToast(item.message, item.url);
                    });

                } catch (e) {
                    console.error('Error notificaciones informe clínico', e);
                }

            }, 10000);

        });
    </script>
    @stack('scripts')
</body>

</html>