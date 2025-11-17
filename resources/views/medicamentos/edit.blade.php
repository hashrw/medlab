<x-medico-layout>

    {{-- BREADCRUMB --}}
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('medicamentos.index') }}" class="hover:text-blue-700">Medicamentos</a>

                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667
                                 c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901
                                 l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>

                <li>
                    <span class="text-gray-500" aria-current="page">
                        Editar {{ $medicamento->nombre }} ({{ $medicamento->miligramos }} mg)
                    </span>
                </li>
            </ol>
        </nav>
    </x-slot>


    {{-- CONTENIDO --}}
    <div class="py-6 px-4">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA BLUE --}}
                <div class="bg-blue-600 text-white p-5">
                    <h3 class="text-lg font-semibold tracking-wide">
                        Editar medicamento
                    </h3>
                </div>

                {{-- FORMULARIO --}}
                <div class="p-6 bg-white border-t border-gray-200">

                    {{-- Errores --}}
                    <x-input-error class="mb-4" :messages="$errors->all()" />

                    <form method="POST" action="{{ route('medicamentos.update', $medicamento->id) }}">
                        @csrf
                        @method('put')

                        {{-- BLOQUE NOMBRE --}}
                        <div class="mb-6">
                            <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1">
                                Nombre común
                            </label>

                            <div class="flex items-center gap-3 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 shadow-sm">
                                <i class="fas fa-capsules text-blue-600"></i>

                                <x-text-input id="nombre" name="nombre" type="text"
                                              class="w-full bg-transparent border-0 focus:ring-0"
                                              :value="$medicamento->nombre" required autofocus />
                            </div>
                        </div>


                        {{-- BLOQUE DOSIS --}}
                        <div class="mb-6">
                            <label for="miligramos" class="block text-sm font-semibold text-gray-700 mb-1">
                                Dosis (mg)
                            </label>

                            <div class="flex items-center gap-3 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 shadow-sm">
                                <i class="fas fa-prescription-bottle text-blue-600"></i>

                                <x-text-input id="miligramos" name="miligramos" type="number"
                                              min="0" step="5"
                                              class="w-full bg-transparent border-0 focus:ring-0"
                                              :value="$medicamento->miligramos" required />
                            </div>
                        </div>


                        {{-- BOTONES --}}
                        <div class="flex justify-end pt-4 border-t border-gray-200 bg-gray-50 -mx-6 px-6 py-4">
                            <a href="{{ route('medicamentos.index') }}"
                               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="ml-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                                Guardar
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-medico-layout>
