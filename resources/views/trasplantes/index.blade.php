<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Módulo de Trasplantes
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                {{-- ENCABEZADO --}}
                <div class="p-6 bg-blue-800 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Lista de Trasplantes</h3>

                    <a href="{{ route('trasplantes.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        + Nuevo Trasplante
                    </a>
                </div>

                {{-- FILTRO CLÍNICO SUPERIOR --}}
                <div class="bg-gray-50 border-b p-4">

                    <form method="GET" action="{{ route('trasplantes.index') }}"
                        class="grid grid-cols-1 md:grid-cols-5 gap-4">

                        {{-- Tipo trasplante --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Tipo</label>
                            <select name="tipo" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Todos</option>

                                <option value="alogénico emparentado" {{ request('tipo') == 'alogénico emparentado' ? 'selected' : '' }}>
                                    Alogénico emparentado
                                </option>

                                <option value="alogénico no emparentado" {{ request('tipo') == 'alogénico no emparentado' ? 'selected' : '' }}>
                                    Alogénico no emparentado
                                </option>

                                <option value="autólogo" {{ request('tipo') == 'autólogo' ? 'selected' : '' }}>
                                    Autólogo
                                </option>

                                <option value="singénico" {{ request('tipo') == 'singénico' ? 'selected' : '' }}>
                                    Singénico
                                </option>
                            </select>
                        </div>

                        {{-- Identidad HLA --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Identidad HLA</label>
                            <select name="hla" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Todas</option>
                                <option value="idéntico" {{ request('hla') == 'idéntico' ? 'selected' : '' }}>Idéntico
                                </option>
                                <option value="disparidad clase I" {{ request('hla') == 'disparidad clase I' ? 'selected' : '' }}>Disparidad clase I</option>
                                <option value="disparidad clase II" {{ request('hla') == 'disparidad clase II' ? 'selected' : '' }}>Disparidad clase II</option>
                            </select>
                        </div>

                        {{-- Serología --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Serología</label>
                            <select name="serologia" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Todas</option>
                                <option value="+" {{ request('serologia') == '+' ? 'selected' : '' }}>+</option>
                                <option value="-" {{ request('serologia') == '-' ? 'selected' : '' }}>-</option>
                            </select>
                        </div>

                        {{-- Año --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Año</label>
                            <input type="number" name="year" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                value="{{ request('year') }}">
                        </div>

                        {{-- Botón filtrar --}}
                        <div class="flex items-end">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md w-full">
                                Filtrar
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Reutilizamos <x-trasplante-card /> --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">

                    @foreach ($trasplantes as $t)
                        <x-trasplante-card :t="$t" />
                    @endforeach

                </div>

                {{-- PAGINACIÓN --}}
                <div class="p-4">
                    {{ $trasplantes->links() }}
                </div>

            </div>

        </div>
    </div>
</x-medico-layout>