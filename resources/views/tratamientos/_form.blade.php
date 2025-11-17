@props([
    'tratamiento' => null,
    'pacientes' => [],
    'pacienteSeleccionado' => null,
])

<div class="p-6 bg-white border-t border-gray-200">

    {{-- Errores --}}
    <x-input-error class="mb-4" :messages="$errors->all()" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- COLUMNA IZQUIERDA --}}
        <div class="space-y-6">

            {{-- Nombre tratamiento --}}
            <div>
                <label for="tratamiento" class="block text-sm font-semibold text-gray-700 mb-1">
                    Nombre del tratamiento
                </label>
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 shadow-sm">
                    <i class="fas fa-prescription text-blue-600"></i>
                    <x-text-input
                        id="tratamiento"
                        name="tratamiento"
                        type="text"
                        class="w-full bg-transparent border-0 focus:ring-0"
                        :value="old('tratamiento', $tratamiento->tratamiento ?? '')"
                        required
                    />
                </div>
            </div>

            {{-- Fecha asignación --}}
            <div>
                <label for="fecha_asignacion" class="block text-sm font-semibold text-gray-700 mb-1">
                    Fecha de asignación
                </label>
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 shadow-sm">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                    <x-text-input
                        id="fecha_asignacion"
                        name="fecha_asignacion"
                        type="date"
                        class="w-full bg-transparent border-0 focus:ring-0"
                        :value="old('fecha_asignacion', isset($tratamiento) ? $tratamiento->fecha_asignacion?->format('Y-m-d') : '')"
                        required
                    />
                </div>
            </div>

            {{-- Paciente --}}
            <div>
                <label for="paciente_id" class="block text-sm font-semibold text-gray-700 mb-1">
                    Paciente
                </label>

                @if($pacienteSeleccionado)
                    {{-- MODO “PACIENTE FIJO” (al crear desde la ficha del paciente) --}}
                    <x-text-input type="hidden" name="paciente_id" :value="$pacienteSeleccionado->id" />

                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 shadow-sm">
                        <i class="fas fa-user-injured text-blue-600"></i>
                        <input type="text"
                               class="w-full bg-transparent border-0 focus:ring-0 text-gray-700"
                               disabled
                               value="{{ $pacienteSeleccionado->user->name }} ({{ $pacienteSeleccionado->nuhsa }})">
                    </div>

                @else
                    {{-- LISTA DE PACIENTES --}}
                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-300 rounded-md px-3 py-2 shadow-sm">
                        <i class="fas fa-user-injured text-blue-600"></i>

                        <select id="paciente_id" name="paciente_id"
                                class="w-full bg-transparent border-0 focus:ring-0 text-gray-700"
                                required>
                            <option value="">Elige un paciente</option>

                            @foreach($pacientes as $p)
                                <option value="{{ $p->id }}"
                                    @selected(old('paciente_id', $tratamiento->paciente_id ?? '') == $p->id)>
                                    {{ $p->user->name }} ({{ $p->nuhsa }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="space-y-6">
            {{-- Descripción --}}
            <div>
                <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1">
                    Descripción
                </label>

                <div class="bg-gray-50 border border-gray-300 rounded-md shadow-sm px-3 py-2">
                    <x-text-area
                        id="descripcion"
                        name="descripcion"
                        class="w-full bg-transparent border-0 focus:ring-0 resize-none h-48"
                        required>{{ old('descripcion', $tratamiento->descripcion ?? '') }}</x-text-area>
                </div>
            </div>
        </div>

    </div>

    {{-- BOTONES --}}
    <div class="flex justify-end mt-8 pt-4 border-t border-gray-200 bg-gray-50 -mx-6 px-6 py-4">
        <a href="{{ route('tratamientos.index') }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
            Cancelar
        </a>

        <button type="submit"
                class="ml-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            Guardar
        </button>
    </div>
</div>
