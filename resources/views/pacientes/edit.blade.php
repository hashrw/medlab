<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('pacientes.index') }}">{{ __('Pacientes') }}</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <a href="#" class="text-gray-500" aria-current="page">{{ __('Editar') }} {{ $paciente->user->name }}</a>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    {{ __('Información clínica del paciente') }}
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Errores de validación en servidor -->
                    <x-input-error class="mb-4" :messages="$errors->all()" />

                    <form method="POST" action="{{ route('pacientes.update', $paciente->id) }}">
                        @csrf
                        @method('put')

                        <!-- Campos del Paciente -->
                        <div>
                            <x-input-label for="nuhsa" :value="__('NUHSA')" />
                            <x-text-input id="nuhsa" class="block mt-1 w-full" type="text" name="nuhsa" :value="$paciente->nuhsa" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                            <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="$paciente->fecha_nacimiento->format('Y-m-d')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="peso" :value="__('Peso (kg)')" />
                            <x-text-input id="peso" class="block mt-1 w-full" type="number" step="0.1" name="peso" :value="$paciente->peso" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="altura" :value="__('Altura (cm)')" />
                            <x-text-input id="altura" class="block mt-1 w-full" type="number" step="1" name="altura" :value="$paciente->altura" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="sexo" :value="__('Sexo')" />
                            <x-select id="sexo" name="sexo" required>
                                <option value="">{{ __('Elige una opción') }}</option>
                                <option value="M" @if ($paciente->sexo == 'M') selected @endif>{{ __('Masculino') }}</option>
                                <option value="F" @if ($paciente->sexo == 'F') selected @endif>{{ __('Femenino') }}</option>
                            </x-select>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end mt-4">
                            <x-danger-button type="button">
                                <a href="{{ route('pacientes.index') }}">
                                    {{ __('Cancelar') }}
                                </a>
                            </x-danger-button>
                            <x-primary-button class="ml-4">
                            <a href="{{ route('pacientes.index') }}">
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>