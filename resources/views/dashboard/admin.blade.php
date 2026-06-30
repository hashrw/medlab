<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de usuarios
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Alta Médico --}}
            <a href="{{ route('admin.usuarios.createMedico') }}"
                class="block p-6 bg-white border rounded-lg shadow hover:border-blue-400 hover:bg-blue-50 transition">
                <h3 class="text-lg font-semibold text-gray-800">
                    Alta de médico
                </h3>
                <p class="mt-2 text-sm text-gray-600">
                    Crear usuario con perfil médico.
                </p>
            </a>

            {{-- Alta Paciente --}}
            <a href="{{ route('admin.usuarios.createPaciente') }}"
                class="block p-6 bg-white border rounded-lg shadow hover:border-green-400 hover:bg-green-50 transition">
                <h3 class="text-lg font-semibold text-gray-800">
                    Alta de paciente
                </h3>
                <p class="mt-2 text-sm text-gray-600">
                    Crear usuario con perfil de paciente.
                </p>
            </a>

        </div>
    </div>
</x-app-layout>