<x-guest-layout>
    <!-- Estado de sesión -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Cabecera -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-blue-900">
            Acceso al sistema
        </h2>
    </div>

    <!-- Formulario -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />

            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div>
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Recordarme -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">

                <span class="ms-2 text-sm text-gray-600">
                    Recordarme
                </span>
            </label>
        </div>


        <!-- Acciones -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button type="button" onclick="history.back()"
                    class="text-sm text-gray-600 hover:text-gray-900 underline">
                    ← Volver atrás
                </button>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-700 hover:text-blue-900 underline" href="{{ route('password.request') }}">
                        ¿Olvidó su contraseña?
                    </a>
                @endif
            </div>

            <x-primary-button>
                {{ __('Acceder') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>