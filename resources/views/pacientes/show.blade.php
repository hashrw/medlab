<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('pacientes.index') }}">{{ __('Pacientes') }}</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                    </svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">{{ __('Datos Clínicos de') }}
                        {{ $paciente->user->name }}</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    {{ __('Información del Paciente') }}
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- {{ dd($paciente) }} --}}
                    <div x-data="{ open: false }">
                        <!-- Botón para abrir el modal -->
                        <button @click="open = true" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            {{ __('Ver Datos Clínicos') }}
                        </button>
                        <!-- Modal -->
                        <div x-show="open"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-6">
                                <!-- Encabezado del modal -->
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-bold">Datos Clínicos de {{ $paciente->user->name }}</h2>
                                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Contenido del modal -->
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label :value="__('Edad')" />
                                        <x-text-input readonly disabled class="block mt-1 w-full"
                                            :value="$paciente->edad . ' años'" />
                                    </div>
                                </div>
                                <!-- Botón para cerrar -->
                                <div class="mt-6 flex justify-end">
                                    <x-danger-button @click="open = false">
                                        {{ __('Cerrar') }}
                                    </x-danger-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Diagnóstico por inferencia del sistema -->
                    <div class="py-8" class="bg-white shadow rounded-lg p-6 border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Diagnóstico Automático</h4>

                        <p class="text-sm text-gray-600 mb-4">
                            Puedes solicitar al sistema que evalúe los síntomas actuales del paciente y genere un
                            diagnóstico basado en las reglas clínicas definidas.
                        </p>

                        <div class="flex items-center justify-end">
                            <form method="POST" action="{{ route('diagnosticos.inferir', $paciente->id) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                                    Inferir Diagnóstico
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>