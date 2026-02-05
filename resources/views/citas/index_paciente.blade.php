<x-paciente-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis solicitudes de cita
        </h2>

        <x-flash-message type="success" />
        <x-flash-message type="warning" />
        <x-flash-message type="error" />
    </x-slot>

    <div class="space-y-4">

        {{-- Acción: Solicitar cita (evita 403 si Policy no permite create) --}}
        @can('create', \App\Models\Cita::class)
            <div class="flex justify-end">
                <a href="{{ route('dashboard.paciente', ['tab' => 'cita']) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                    Solicitar cita
                </a>
            </div>
        @endcan

        <div class="overflow-x-auto border rounded">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">ID</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Estado</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Motivo</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Preferencia</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-600">Fecha confirmada</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($citas as $cita)
                        @php
                            $pref = $cita->preferencia_fecha_hora?->format('d/m/Y H:i') ?? '-';
                            $fh = $cita->fecha_hora?->format('d/m/Y H:i') ?? '-';

                            $mot = $cita->motivo ?? '-';
                            if ($mot === 'Otro' && !empty($cita->motivo_detalle)) {
                                $mot .= ' - ' . $cita->motivo_detalle;
                            }

                            $estado = $cita->estado ?? 'pendiente';

                            $badge = match($estado) {
                                'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'aceptada' => 'bg-green-100 text-green-800 border-green-200',
                                'rechazada' => 'bg-red-100 text-red-800 border-red-200',
                                'cancelada' => 'bg-gray-100 text-gray-700 border-gray-200',
                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                        @endphp

                        <tr>
                            <td class="px-3 py-2 font-medium text-gray-900">{{ $cita->id }}</td>

                            <td class="px-3 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded border text-xs {{ $badge }}">
                                    {{ ucfirst($estado) }}
                                </span>
                            </td>

                            <td class="px-3 py-2">{{ $mot }}</td>
                            <td class="px-3 py-2">{{ $pref }}</td>
                            <td class="px-3 py-2">{{ $fh }}</td>

                            <td class="px-3 py-2 text-right">
                                {{-- Acción: Ver (evita 403 si Policy no permite view) --}}
                                @can('view', $cita)
                                    <a href="{{ route('citas.show', $cita->id) }}"
                                       class="text-blue-600 hover:text-blue-800 underline">
                                        Ver
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">No autorizado</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-gray-600">
                                No hay solicitudes de cita.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $citas->links() }}
    </div>
</x-paciente-layout>
