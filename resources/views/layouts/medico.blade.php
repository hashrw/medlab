<!-- resources/views/layouts/medico.blade.php -->
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

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/css/layouts/medico.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="app-wrapper">
        <!-- Top Navigation -->
        <div class="nav-container max-w-[1440px] mx-auto">
            <div class="nav-content">
                @include('layouts.navigation')
            </div>
        </div>

        <!-- Main Container -->
        <div class="main-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                @include('layouts.sidebar-medico')
            </aside>

            <!-- Overlay (Mobile) -->
            <div class="overlay" id="overlay"></div>

            <!-- Main Content -->
            <main class="content">
                @if (isset($header))
                    <header class="border-b border-gray-200 bg-white">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                @if (session()->has('warning'))
                    <x-alert type="danger" :message="session('warning')" />
                @endif

                @if (session()->has('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <button id="menu-toggle"
        class="md:hidden fixed bottom-6 right-6 bg-blue-500 text-white p-3 rounded-full shadow-lg z-50">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });
        document.getElementById('overlay').addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    </script>
</body>

</html>