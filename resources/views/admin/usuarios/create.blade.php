<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alta administrativa
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <div class="text-lg font-semibold text-gray-800">Crear usuario clínico</div>
                <p class="text-sm text-gray-600 mt-1">
                    El alta se realiza desde backoffice. No existe registro público.
                </p>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.usuarios.createPaciente') }}"
                       class="border rounded-lg p-5 hover:bg-gray-50">
                        <div class="text-sm uppercase tracking-wider text-gray-500 font-semibold">Paciente</div>
                        <div class="text-base font-semibold text-gray-800 mt-1">Crear paciente</div>
                        <div class="text-sm text-gray-600 mt-2">
                            Crea usuario + ficha clínica del paciente y asigna médico si procede.
                        </div>
                    </a>

                    <a href="{{ route('admin.usuarios.createMedico') }}"
                       class="border rounded-lg p-5 hover:bg-gray-50">
                        <div class="text-sm uppercase tracking-wider text-gray-500 font-semibold">Médico</div>
                        <div class="text-base font-semibold text-gray-800 mt-1">Crear médico</div>
                        <div class="text-sm text-gray-600 mt-2">
                            Crea usuario + perfil médico y especialidad.
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
