<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('citas.index') }}">Citas</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-500" aria-current="page">Ver cita</span>
                </li>
            </ol>
        </nav>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    @php
        $estado = $cita->estado ?? 'pendiente';

        $pacienteNombre = $cita->paciente?->user?->name
            ?? $cita->paciente?->usuarioAcceso?->name
            ?? ('Paciente #' . ($cita->paciente_id ?? '-'));

        $pacienteNuhsa = $cita->paciente?->nuhsa ?? null;

        $medicoNombre = $cita->medico?->user?->name ?? '—';
        $medicoEspecialidad = $cita->medico?->especialidad?->nombre ?? null;

        $fechaCita = $cita->fecha_hora ? $cita->fecha_hora->format('d/m/Y H:i') : '—';
        $preferencia = $cita->preferencia_fecha_hora ? $cita->preferencia_fecha_hora->format('d/m/Y H:i') : '—';

        $badge = match($estado) {
            'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'aceptada' => 'bg-green-100 text-green-800 border-green-200',
            'rechazada' => 'bg-red-100 text-red-800 border-red-200',
            'cancelada' => 'bg-gray-100 text-gray-700 border-gray-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    @endphp

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex items-center justify-between">
                    <div class="font-semibold text-lg text-gray-800">
                        Cita #{{ $cita->id }}
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded border text-xs {{ $badge }}">
                        {{ ucfirst($estado) }}
                    </span>
                </div>

                <div class="p-6 bg-white border-b border-gray-200 space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Paciente</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $pacienteNombre }}@if($pacienteNuhsa) ({{ $pacienteNuhsa }})@endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Médico asignado</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $medicoNombre }}@if($medicoEspecialidad) ({{ $medicoEspecialidad }})@endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Fecha/hora cita</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $fechaCita }}
                            </div>
                            @if($estado === 'pendiente')
                                <div class="text-xs text-gray-500 mt-1">
                                    En pendiente puede estar vacía hasta que aceptes.
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Preferencia paciente</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $preferencia }}
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <div class="text-xs text-gray-500">Motivo</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $cita->motivo ?? '—' }}
                            </div>

                            @if(!empty($cita->motivo_detalle))
                                <div class="text-sm text-gray-700 mt-2 whitespace-pre-line">
                                    {{ $cita->motivo_detalle }}
                                </div>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <div class="text-xs text-gray-500">Comentario del médico</div>
                            <div class="text-sm text-gray-900 whitespace-pre-line">
                                {{ $cita->comentario_medico ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Respondida</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $cita->respondida_at ? $cita->respondida_at->format('d/m/Y H:i') : '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Creada</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $cita->created_at ? $cita->created_at->format('d/m/Y H:i') : '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between">
                        <a href="{{ route('citas.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Volver
                        </a>

                        <div class="flex gap-2">
                            <a href="{{ route('citas.edit', $cita->id) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                Editar
                            </a>

                            @if($estado === 'pendiente')
                                <form method="POST" action="{{ route('citas.aceptar', $cita->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded"
                                            onclick="return confirm('¿Aceptar la solicitud? Te llevará a editar/confirmar fecha si procede.')">
                                        Aceptar
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('citas.rechazar', $cita->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded"
                                            onclick="return confirm('¿Rechazar la solicitud?')">
                                        Rechazar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-medico-layout>
