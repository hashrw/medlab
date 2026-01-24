<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('citas.index') }}">Citas</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                    </svg>
                </li>
                <li>
                    <span class="text-gray-500" aria-current="page">Editar cita</span>
                </li>
            </ol>
        </nav>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    @php
        $pacienteNombre = $cita->paciente?->user?->name
            ?? $cita->paciente?->usuarioAcceso?->name
            ?? ('Paciente #' . ($cita->paciente_id ?? '-'));

        $pacienteNuhsa = $cita->paciente?->nuhsa ?? null;

        $medicoNombre = $cita->medico?->user?->name ?? '—';
        $medicoEspecialidad = $cita->medico?->especialidad?->nombre ?? null;

        $estadoActual = old('estado', $cita->estado ?? 'pendiente');

        // datetime-local: valor en Y-m-d\TH:i
        $fechaHoraValue = old('fecha_hora');
        if ($fechaHoraValue === null && $cita->fecha_hora) {
            $fechaHoraValue = $cita->fecha_hora->format('Y-m-d\TH:i');
        }

        $prefValue = old('preferencia_fecha_hora');
        if ($prefValue === null && $cita->preferencia_fecha_hora) {
            $prefValue = $cita->preferencia_fecha_hora->format('Y-m-d\TH:i');
        }
    @endphp

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <div class="font-semibold text-lg text-gray-800">
                        Editar cita #{{ $cita->id }}
                    </div>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <x-input-error class="mb-4" :messages="$errors->all()" />

                    <form method="POST" action="{{ route('citas.update', $cita->id) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="md:col-span-2">
                                <div class="text-xs text-gray-500">Paciente</div>
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $pacienteNombre }}@if($pacienteNuhsa) ({{ $pacienteNuhsa }})@endif
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <div class="text-xs text-gray-500">Médico</div>
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $medicoNombre }}@if($medicoEspecialidad) ({{ $medicoEspecialidad }})@endif
                                </div>
                            </div>

                            <div>
                                <x-input-label for="estado" :value="__('Estado')" />
                                <select id="estado" name="estado" class="mt-1 block w-full border-gray-300 rounded">
                                    @foreach (['pendiente', 'aceptada', 'rechazada', 'cancelada'] as $st)
                                        <option value="{{ $st }}" @selected($estadoActual === $st)>
                                            {{ ucfirst($st) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="fecha_hora" :value="__('Fecha y hora (cita)')" />
                                <input id="fecha_hora" class="block mt-1 w-full border-gray-300 rounded"
                                    type="datetime-local" name="fecha_hora" value="{{ $fechaHoraValue ?? '' }}">
                                <div class="text-xs text-gray-500 mt-1">
                                    Puede estar vacía si sigue en pendiente.
                                </div>
                            </div>

                            <div>
                                <x-input-label for="preferencia_fecha_hora" :value="__('Preferencia paciente (opcional)')" />
                                <input id="preferencia_fecha_hora" class="block mt-1 w-full border-gray-300 rounded"
                                    type="datetime-local" name="preferencia_fecha_hora" value="{{ $prefValue ?? '' }}">
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="motivo" :value="__('Motivo')" />
                                <input id="motivo" class="block mt-1 w-full border-gray-300 rounded" type="text"
                                    name="motivo" maxlength="120" value="{{ old('motivo', $cita->motivo ?? '') }}">
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="motivo_detalle" :value="__('Detalle del motivo (opcional)')" />
                                <textarea id="motivo_detalle" name="motivo_detalle" rows="4"
                                    class="block mt-1 w-full border-gray-300 rounded"
                                    maxlength="2000">{{ old('motivo_detalle', $cita->motivo_detalle ?? '') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="comentario_medico" :value="__('Comentario al paciente (opcional)')" />
                                <textarea id="comentario_medico" name="comentario_medico" rows="3"
                                    class="block mt-1 w-full border-gray-300 rounded"
                                    maxlength="2000">{{ old('comentario_medico', $cita->comentario_medico ?? '') }}</textarea>
                            </div>

                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <a href="{{ route('citas.show', $cita->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                Guardar
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</x-medico-layout>