<x-medico-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Citas (Bandeja médico)
            </h2>

            <a href="{{ route('citas.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Crear cita
            </a>
        </div>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    <div class="py-1">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg overflow-hidden">

                {{-- FILTROS --}}
                <div class="p-4 border-b bg-gray-50">
                    <form method="GET" action="{{ route('citas.index') }}" class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div class="flex flex-col md:flex-row gap-3 md:items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <select name="estado" class="mt-1 block w-full border-gray-300 rounded">
                                    <option value="">Todos</option>
                                    @foreach (['pendiente','aceptada','rechazada','cancelada'] as $st)
                                        <option value="{{ $st }}" @selected(request('estado') === $st)>
                                            {{ ucfirst($st) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                                    Filtrar
                                </button>

                                <a href="{{ route('citas.index') }}"
                                   class="inline-flex items-center px-4 py-2 ml-2 text-gray-600 hover:text-gray-900">
                                    Limpiar
                                </a>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600">
                            Mostrando {{ $citas->count() }} de {{ $citas->total() }}
                        </div>
                    </form>
                </div>

                {{-- TABLA --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">ID</th>
                                <th class="px-4 py-3 text-left">Paciente</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Fecha cita</th>
                                <th class="px-4 py-3 text-left">Preferencia paciente</th>
                                <th class="px-4 py-3 text-left">Motivo</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($citas as $cita)
                                @php
                                    $estado = $cita->estado ?? 'pendiente';
                                    $isPendiente = ($estado === 'pendiente');
                                    $fechaCita = $cita->fecha_hora ? $cita->fecha_hora->format('d/m/Y H:i') : '-';
                                    $pref = $cita->preferencia_fecha_hora ? $cita->preferencia_fecha_hora->format('d/m/Y H:i') : '-';

                                    $motivoTxt = $cita->motivo ?? '-';
                                    if ($cita->motivo === 'otro' && !empty($cita->motivo_detalle)) {
                                        $motivoTxt = 'Otro: ' . $cita->motivo_detalle;
                                    }

                                    // Nombre paciente (compatibilidad: user o usuarioAcceso)
                                    $pUserName = null;
                                    if ($cita->paciente) {
                                        $pUserName = $cita->paciente->user->name ?? $cita->paciente->usuarioAcceso->name ?? null;
                                    }
                                @endphp

                                <tr class="{{ $isPendiente ? 'bg-yellow-50' : 'bg-white' }}">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $cita->id }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="text-gray-900 font-medium">
                                            {{ $pUserName ?? ('Paciente #' . ($cita->paciente_id ?? '-')) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID: {{ $cita->paciente_id ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        @php
                                            $badge = match($estado) {
                                                'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'aceptada' => 'bg-green-100 text-green-800 border-green-200',
                                                'rechazada' => 'bg-red-100 text-red-800 border-red-200',
                                                'cancelada' => 'bg-gray-100 text-gray-700 border-gray-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded border text-xs {{ $badge }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $fechaCita }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $pref }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="text-gray-800">
                                            {{ $motivoTxt }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">

                                            <a href="{{ route('citas.show', $cita->id) }}"
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                                Ver
                                            </a>

                                            <a href="{{ route('citas.edit', $cita->id) }}"
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                                Editar
                                            </a>

                                            @if($isPendiente)
                                                {{-- ACEPTAR --}}
                                                <button type="button"
                                                        onclick="openAceptarModal({{ $cita->id }}, '{{ e($pref !== '-' ? $pref : '') }}')"
                                                        class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                                                    Aceptar
                                                </button>

                                                {{-- RECHAZAR --}}
                                                <button type="button"
                                                        onclick="openRechazarModal({{ $cita->id }})"
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                                                    Rechazar
                                                </button>
                                            @endif

                                            <form method="POST" action="{{ route('citas.destroy', $cita->id) }}"
                                                  onsubmit="return confirm('¿Eliminar esta cita?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-600">
                                        No hay citas para mostrar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t">
                    {{ $citas->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ACEPTAR --}}
    <div id="modal-aceptar"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-blue-700 text-white flex items-center justify-between">
                <div>
                    <div class="text-lg font-semibold">Aceptar solicitud</div>
                    <div class="text-xs text-blue-100">Se asignará fecha/hora y se responderá al paciente.</div>
                </div>
                <button type="button" onclick="closeAceptarModal()" class="text-white hover:text-gray-200 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <form method="POST" id="form-aceptar" action="">
                @csrf
                @method('PATCH')

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha y hora de la cita</label>
                        <input type="datetime-local" name="fecha_hora" id="aceptar-fecha"
                               class="mt-1 block w-full border-gray-300 rounded" required>
                        <p class="text-xs text-gray-500 mt-1">
                            Si el paciente indicó preferencia, úsala como guía.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Comentario al paciente (opcional)</label>
                        <textarea name="comentario_medico" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2">
                    <button type="button"
                            onclick="closeAceptarModal()"
                            class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL RECHAZAR --}}
    <div id="modal-rechazar"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-blue-700 text-white flex items-center justify-between">
                <div>
                    <div class="text-lg font-semibold">Rechazar solicitud</div>
                    <div class="text-xs text-blue-100">Se notificará al paciente (opcional comentario).</div>
                </div>
                <button type="button" onclick="closeRechazarModal()" class="text-white hover:text-gray-200 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <form method="POST" id="form-rechazar" action="">
                @csrf
                @method('PATCH')

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Comentario al paciente (opcional)</label>
                        <textarea name="comentario_medico" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2">
                    <button type="button"
                            onclick="closeRechazarModal()"
                            class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                        Confirmar rechazo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAceptarModal(citaId) {
            const modal = document.getElementById('modal-aceptar');
            const form = document.getElementById('form-aceptar');

            form.action = "{{ url('/citas') }}/" + citaId + "/aceptar";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAceptarModal() {
            const modal = document.getElementById('modal-aceptar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openRechazarModal(citaId) {
            const modal = document.getElementById('modal-rechazar');
            const form = document.getElementById('form-rechazar');

            form.action = "{{ url('/citas') }}/" + citaId + "/rechazar";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRechazarModal() {
            const modal = document.getElementById('modal-rechazar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-medico-layout>
