@props(['t'])
<div class="bg-white border rounded-xl shadow hover:shadow-lg transition p-5">
    {{-- Paciente asociado --}}
    <div class="flex items-center gap-3 mb-3">

        @if($t->paciente && $t->paciente->avatar)
            <img src="{{ asset('storage/' . $t->paciente->avatar) }}" class="w-10 h-10 rounded-full object-cover">
        @else
            <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center text-blue-800 font-bold">
                {{ strtoupper(substr($t->paciente->nombre, 0, 1)) }}
            </div>
        @endif

        <a href="{{ route('pacientes.show', $t->paciente_id) }}"
            class="text-blue-700 font-semibold hover:text-blue-900">
            {{ $t->paciente->nombre }}
        </a>
    </div>

    {{-- Cabecera con tipo + año --}}
    <div class="flex justify-between items-center border-b pb-2 mb-3">

        <h4 class="font-semibold text-blue-800 flex items-center gap-2">
            <i class="fas fa-dna text-blue-600"></i>
            {{ $t->tipo_trasplante }}
        </h4>

        <span class="text-xs text-gray-600">
            <i class="fas fa-calendar-alt"></i>
            {{ $t->fecha_trasplante->format('Y') }}
        </span>

    </div>

    {{-- Cuerpo --}}
    <div class="space-y-2 text-sm">

        <p>
            <span class="font-semibold">Fecha:</span>
            {{ $t->fecha_trasplante->format('d/m/Y') }}
        </p>

        <p>
            <span class="font-semibold">Origen:</span>
            {{ $t->origen_trasplante }}
        </p>

        <p>
            <span class="font-semibold">HLA:</span>
            <span class="inline-flex items-center gap-1">
                <i class="fas fa-shield-virus text-gray-600"></i>
                {{ $t->identidad_hla }}
            </span>
        </p>

        <p>
            <span class="font-semibold">Acondicionamiento:</span>
            {{ $t->tipo_acondicionamiento }}
        </p>

        <p>
            <span class="font-semibold">Serología:</span>
            {{ $t->seropositividad_donante }} / {{ $t->seropositividad_receptor }}
        </p>

        {{-- Acciones --}}
        <div class="pt-2 flex justify-end space-x-3">

            <a href="{{ route('trasplantes.show', $t->id) }}" class="hover:text-blue-600">
                <i class="fas fa-eye"></i>
            </a>

            <a href="{{ route('trasplantes.edit', $t->id) }}" class="hover:text-yellow-600">
                <i class="fas fa-edit"></i>
            </a>

            <form method="POST" action="{{ route('trasplantes.destroy', $t->id) }}"
                onsubmit="return confirm('¿Eliminar?')">
                @csrf
                @method('DELETE')
                <button class="hover:text-red-600">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>