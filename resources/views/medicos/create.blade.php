<x-app-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
              <li class="flex items-center">
                <a href="{{ route('medicos.index') }}">Médicos</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
              </li>
              <li>
                <a href="#" class="text-gray-500" aria-current="page">Crear médico</a>
              </li>
            </ol>
          </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="font-semibold text-lg px-6 py-4 bg-white border-b border-gray-200">
                    Información del médico
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Errores de validación en servidor -->
                        <x-input-error class="mb-4" :messages="$errors->all()" />
                        <form method="POST" action="{{ route('medicos.store') }}">
                            @csrf
                            <div>
                                <x-input-label for="name" :value="__('Nombre')" />

                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="email" :value="__('Email')" />

                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Contraseña')" />

                                <x-text-input id="password" class="block mt-1 w-full"
                                                type="password"
                                                name="password"
                                                required autocomplete="new-password" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />

                                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                type="password"
                                                name="password_confirmation" required />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="fecha_contratacion" :value="__('Fecha contratación')" />

                                <x-text-input id="fecha_contratacion" class="block mt-1 w-full"
                                                type="date"
                                                name="fecha_contratacion"
                                                :value="old('fecha_contratacion')"
                                                required />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="vacunado" :value="__('Vacunado')" />


                                <x-select id="vacunado" name="vacunado" required>
                                    <option value="">{{__('Elige una opción')}}</option>
                                    <option value="1" @if (old('vacunado') == 1) selected @endif>{{__('Sí')}}</option>
                                    <option value="0" @if (old('vacunado') == 0) selected @endif>{{__('No')}}</option>
                                </x-select>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="sueldo" :value="__('Sueldo')" />

                                <x-text-input id="sueldo" class="block mt-1 w-full" min="0" step="1" type="number" name="sueldo" :value="old('sueldo')" required />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="especialidad_id" :value="__('Especialidad')" />


                                <x-select id="especialidad_id" name="especialidad_id" required>
                                    <option value="">{{__('Elige una opción')}}</option>
                                    @foreach ($especialidads as $especialidad)
                                    <option value="{{$especialidad->id}}" @if (old('especialidad_id') == $especialidad->id) selected @endif>{{$especialidad->nombre}}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-danger-button type="button">
                                    <a href={{route('medicos.index')}}>
                                    {{ __('Cancelar') }}
                                    </a>
                                </x-danger-button>
                                <x-primary-button class="ml-4">
                                    {{ __('Guardar') }}
                                </x-primary-button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
