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
        <div class="flex justify-end">
            <a href="{{ route('dashboard.paciente', ['tab' => 'cita']) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Solicitar cita
            </a>
        </div>

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
                    @foreach ($citas as $cita)
                        @php
                            $pref = $cita->preferencia_fecha_hora?->format('d/m/Y H:i') ?? '-';
                            $fh = $cita->fecha_hora?->format('d/m/Y H:i') ?? '-';
                            $mot = $cita->motivo ?? '-';
                            if ($mot === 'Otro' && $cita->motivo_detalle) $mot .= ' - ' . $cita->motivo_detalle;
                        @endphp
                        <tr>
                            <td class="px-3 py-2 font-medium text-gray-900">{{ $cita->id }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded text-xs border">
                                    {{ $cita->estado }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $mot }}</td>
                            <td class="px-3 py-2">{{ $pref }}</td>
                            <td class="px-3 py-2">{{ $fh }}</td>
                            <td class="px-3 py-2 text-right">
                                <a href="{{ route('citas.show', $cita->id) }}"
                                   class="text-blue-600 hover:text-blue-800 underline">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $citas->links() }}
    </div>
</x-paciente-layout>
