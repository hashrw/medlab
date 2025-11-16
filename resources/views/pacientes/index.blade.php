<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Módulo de Pacientes
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Lista de Pacientes</h3>

                    <a href="{{ route('pacientes.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        + Crear Paciente
                    </a>
                </div>

                {{-- FILTROS CLÍNICOS --}}
                <div class="bg-gray-50 border-b p-4">

                    <form method="GET" action="{{ route('pacientes.index') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        {{-- Nombre --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ request('nombre') }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        {{-- Sexo --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Sexo</label>
                            <select name="sexo" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Todos</option>
                                <option value="M" {{ request('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ request('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        {{-- Edad mínima --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Edad mínima</label>
                            <input type="number" name="edad_min" value="{{ request('edad_min') }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        {{-- Edad máxima --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Edad máxima</label>
                            <input type="number" name="edad_max" value="{{ request('edad_max') }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        {{-- IMC --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">IMC</label>
                            <select name="imc" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Todos</option>
                                <option value="normal"    {{ request('imc') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="sobrepeso" {{ request('imc') == 'sobrepeso' ? 'selected' : '' }}>Sobrepeso</option>
                                <option value="obesidad"  {{ request('imc') == 'obesidad' ? 'selected' : '' }}>Obesidad</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md w-full">
                                Filtrar
                            </button>
                        </div>

                    </form>
                </div>

                {{-- TARJETAS: USO DEL COMPONENTE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">

                    @foreach ($pacientes as $paciente)
                        <x-paciente-card :paciente="$paciente" />
                    @endforeach

                </div>

                {{-- PAGINACIÓN --}}
                <div class="p-4">
                    {{ $pacientes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>
