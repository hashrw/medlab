<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight">
            <ol class="inline-flex items-center space-x-2">
                <li>
                    <a href="{{ route('tratamientos.index') }}" class="hover:text-blue-700">Tratamientos</a>
                </li>
                <li class="text-gray-500">› Editar {{ $tratamiento->tratamiento }}</li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

            <div class="bg-blue-600 text-white p-5">
                <h3 class="text-lg font-semibold tracking-wide">Editar tratamiento</h3>
            </div>

            <form method="POST" action="{{ route('tratamientos.update', $tratamiento->id) }}">
                @csrf
                @method('PUT')

                @include('tratamientos._form', [
                    'tratamiento' => $tratamiento,
                    'pacientes' => $pacientes,
                    'pacienteSeleccionado' => $pacienteSeleccionado
                ])
            </form>

            {{-- Líneas de tratamiento (pivot medicamento_tratamiento) --}}
            <div id="lineas-tratamiento" class="border-t border-gray-200">
                <div class="p-5 bg-gray-50">
                    <h4 class="text-base font-semibold text-gray-800">Líneas de tratamiento</h4>
                    <p class="text-sm text-gray-600 mt-1">Cierra una línea estableciendo la fecha fin.</p>
                </div>

                <div class="p-6">

                    {{-- Flash específico para acciones de líneas (evita “mensaje arriba”) --}}
                    @if(session('success_linea'))
                        <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                            {{ session('success_linea') }}
                        </div>
                    @endif

                    @if(session('error_linea'))
                        <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            {{ session('error_linea') }}
                        </div>
                    @endif

                    {{-- Errores específicos de cierre (validateWithBag('cerrar', ...)) --}}
                    @if($errors->cerrar->any())
                        <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->cerrar->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $lineas = $tratamiento->relationLoaded('lineasTratamiento')
                            ? $tratamiento->lineasTratamiento
                            : $tratamiento->lineasTratamiento()->get();
                    @endphp

                    @if($lineas->isEmpty())
                        <div class="text-sm text-gray-600">
                            Este tratamiento no tiene líneas asociadas todavía.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm border border-gray-200 rounded-md overflow-hidden">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="text-left px-4 py-3 border-b">Medicamento</th>
                                        <th class="text-left px-4 py-3 border-b">Inicio</th>
                                        <th class="text-left px-4 py-3 border-b">Fin</th>
                                        <th class="text-left px-4 py-3 border-b">Duración</th>
                                        <th class="text-right px-4 py-3 border-b">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($lineas as $m)
                                        @php
                                            $ini = $m->pivot->fecha_ini_linea;
                                            $fin = $m->pivot->fecha_fin_linea; // null = abierta
                                            $dur = $m->pivot->duracion_linea;

                                            $cerrada = !is_null($fin);

                                            // Si el submit falló por validación, solo repintamos el old() en la fila del medicamento enviado
                                            $oldMedId = old('medicamento_id');
                                            $fechaFinInput = ($oldMedId && (int)$oldMedId === (int)$m->id)
                                                ? old('fecha_fin_linea')
                                                : ($fin ? \Carbon\Carbon::parse($fin)->format('Y-m-d') : '');
                                        @endphp

                                        <tr class="border-b">
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-gray-800">{{ $m->nombre }}</div>
                                                <div class="text-xs text-gray-500">ID: {{ $m->id }}</div>
                                            </td>

                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $ini ? \Carbon\Carbon::parse($ini)->format('Y-m-d') : '-' }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $fin ? \Carbon\Carbon::parse($fin)->format('Y-m-d') : 'Abierta' }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-700">
                                                {{ is_null($dur) ? '-' : $dur . ' días' }}
                                            </td>

                                            <td class="px-4 py-3">
                                                <div class="flex justify-end gap-2">
                                                    {{-- Cerrar línea (PATCH) --}}
                                                    <form method="POST"
                                                          action="{{ route('tratamientos.cerrarLinea', $tratamiento->id) }}"
                                                          class="flex items-center gap-2">
                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="hidden" name="medicamento_id" value="{{ $m->id }}">

                                                        <input
                                                            type="date"
                                                            name="fecha_fin_linea"
                                                            value="{{ $fechaFinInput }}"
                                                            class="border border-gray-300 rounded px-2 py-1 text-sm"
                                                            @disabled($cerrada)
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow disabled:opacity-50 disabled:cursor-not-allowed"
                                                            @disabled($cerrada)
                                                        >
                                                            Cerrar
                                                        </button>
                                                    </form>

                                                    {{-- Eliminar línea (DELETE) --}}
                                                    <form method="POST" action="{{ route('tratamientos.detachLinea', [$tratamiento->id, $m->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow"
                                                                onclick="return confirm('¿Seguro que quieres eliminar esta línea?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>

                                                @if($cerrada)
                                                    <div class="mt-1 text-xs text-gray-500 text-right">Línea cerrada</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-medico-layout>
