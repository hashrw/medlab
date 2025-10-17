<x-medico-layout>
    <x-slot name="header">
        <div class="p-4 bg-blue-600 text-white flex justify-between items-center rounded-md shadow-sm">
            <h3 class="text-lg font-semibold tracking-wide">Editar síntoma</h3>
            <a href="{{ route('sintomas.index') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
                Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-1 px-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                <!-- Cabecera interior -->
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h4 class="text-md font-semibold text-gray-700">Actualiza los datos del síntoma</h4>
                </div>

                <div class="p-6 bg-white">
                    <!-- Errores de validación -->
                    <x-input-error class="mb-4" :messages="$errors->all()" />

                    <form method="POST" action="{{ route('sintomas.update', $sintoma->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Campo Síntoma --}}
                        <div class="mt-4">
                            <x-input-label for="sintoma" :value="__('Síntoma')" />
                            <x-text-input id="sintoma" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          type="text" name="sintoma" :value="$sintoma->sintoma" required />
                        </div>

                        {{-- Campo Manifestación Clínica --}}
                        <div class="mt-4">
                            <x-input-label for="manif_clinica" :value="__('Manifestación clínica')" />
                            <x-text-input id="manif_clinica" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          type="text" name="manif_clinica" :value="$sintoma->manif_clinica" required />
                        </div>

                        {{-- Campo Categoría --}}
                        <div class="mt-4">
                            <x-input-label for="categoria" :value="__('Categoría')" />
                            <x-text-input id="categoria" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          type="text" name="categoria" :value="$sintoma->categoria" placeholder="Ejemplo: Mucosa oral, Articular, Cutánea..." />
                        </div>

                        {{-- Campo Órgano asociado --}}
                        <div class="mt-4">
                            <x-input-label for="organo_id" :value="__('Órgano asociado')" />
                            <x-select id="organo_id" name="organo_id" required
                                      class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('Elige un órgano') }}</option>
                                @foreach ($organos as $organo)
                                    <option value="{{ $organo->id }}"
                                            @selected($sintoma->organo_id == $organo->id)>
                                        {{ $organo->nombre }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('sintomas.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit"
                                    class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition">
                                {{ __('Guardar cambios') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>
