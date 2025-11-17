<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="{{ route('tratamientos.index') }}" class="hover:text-blue-700">Tratamientos</a></li>
                <li class="text-gray-500">› Editar {{ $tratamiento->tratamiento }}</li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

            <div class="bg-blue-600 text-white p-5">
                <h3 class="text-lg font-semibold tracking-wide">Editar tratamiento</h3>
            </div>

            <form method="POST" action="{{ route('tratamientos.update', $tratamiento->id) }}">
                @csrf
                @method('PUT')

                @include('tratamientos._form', [
                    'tratamiento' => $tratamiento,
                    'pacientes' => $pacientes,
                    'pacienteSeleccionado' => null
                ])
            </form>

        </div>
    </div>
</x-medico-layout>
