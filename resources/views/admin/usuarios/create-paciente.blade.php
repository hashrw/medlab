<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alta de paciente
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">

            <form method="POST" action="{{ route('admin.usuarios.storePaciente') }}">
                @csrf

                {{-- DATOS DE ACCESO --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Datos de acceso
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="password" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                {{-- DATOS CLÍNICOS DEL PACIENTE --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Datos clínicos del paciente
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NUHSA</label>
                        <input type="text" name="nuhsa" value="{{ old('nuhsa') }}" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('nuhsa') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('fecha_nacimiento') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sexo</label>
                        <select name="sexo" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Seleccionar</option>
                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                        </select>
                        @error('sexo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard.admin') }}" class="px-4 py-2 border rounded-md text-gray-700">
                        Cancelar
                    </a>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                        Crear paciente
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>