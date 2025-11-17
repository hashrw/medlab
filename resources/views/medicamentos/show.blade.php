<x-medico-layout>
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
                    <span class="text-gray-500">Consultar datos</span>
                </li>
            </ol>
        </nav>
    </x-slot>

    {{-- CONTENIDO --}}
    <div class="py-1 px-4">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA --}}
                <div class="bg-blue-600 text-white p-5">
                    <h3 class="text-lg font-semibold tracking-wide">
                        Información del medicamento
                    </h3>
                </div>

                {{-- CUERPO --}}
                <div class="p-6 space-y-6">

                    {{-- Nombre del medicamento --}}
                    <div class="flex items-start gap-3">
                        <i class="fas fa-capsules text-blue-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Nombre común</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $medicamento->nombre }}</p>
                        </div>
                    </div>

                    {{-- Dosis --}}
                    <div class="flex items-start gap-3">
                        <i class="fas fa-prescription-bottle text-blue-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Dosis (mg)</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $medicamento->miligramos }} mg</p>
                        </div>
                    </div>

                </div>

                {{-- BOTÓN VOLVER --}}
                <div class="px-6 py-4 bg-gray-50 flex justify-end border-t border-gray-200">
                    <a href="{{ route('medicamentos.index') }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
                        Volver
                    </a>
                </div>

            </div>

        </div>
    </div>

</x-medico-layout>
