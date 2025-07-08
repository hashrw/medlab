<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('pruebas.index') }}">Pruebas clínicas</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-500" aria-current="page">Editar prueba</span>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 bg-blue-800 text-white">
                    <h3 class="text-lg font-semibold">Editar prueba clínica</h3>
                </div>

                <div class="p-6 bg-white border-t border-gray-200">
                    <!-- Errores de validación -->
                    <x-input-error class="mb-4" :messages="$errors->all()" />

                    <form method="POST" action="{{ route('pruebas.update', $prueba->id) }}">
                        @csrf
                        @method('put')

                        <div class="mt-4">
                            <x-input-label for="nombre" :value="__('Nombre de la prueba')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text"
                                          name="nombre" :value="$prueba->nombre" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tipo_prueba" :value="__('Tipo de prueba')" />
                            <x-text-input id="tipo_prueba" class="block mt-1 w-full" type="text"
                                          name="tipo_prueba" :value="$prueba->tipo_prueba" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fecha" :value="__('Fecha de la prueba')" />
                            <x-text-input id="fecha" class="block mt-1 w-full" type="date"
                                          name="fecha" :value="$prueba->fecha" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="resultado" :value="__('Resultado')" />
                            <x-text-input id="resultado" class="block mt-1 w-full" type="text"
                                          name="resultado" :value="$prueba->resultado" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="comentario" :value="__('Comentario')" />
                            <x-text-input id="comentario" class="block mt-1 w-full" type="text"
                                          name="comentario" :value="$prueba->comentario" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('pruebas.index') }}"
                               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit"
                                    class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                {{ __('Guardar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
