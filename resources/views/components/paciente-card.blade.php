@props(['paciente'])

<div class="bg-white border rounded-xl shadow hover:shadow-lg transition p-5">

    {{-- CABECERA --}}
    <div class="flex justify-between items-center border-b pb-2 mb-3">
        <h4 class="font-semibold text-blue-800">
            {{ $paciente->nombre }}
        </h4>

        <div class="flex space-x-3 text-gray-600">

            <a href="{{ route('pacientes.show', $paciente->id) }}" class="text-blue-600 hover:text-blue-800"
                title="Ver">
                <i class="fas fa-eye"></i>
            </a>

            @can('update', $paciente)
                <a href="{{ route('pacientes.edit', $paciente->id) }}" class="text-yellow-600 hover:text-yellow-700"
                    title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
            @endcan

            @can('delete', $paciente)
                <form method="POST" action="{{ route('pacientes.destroy', $paciente->id) }}"
                    onsubmit="return confirm('¿Eliminar este paciente?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            @endcan

        </div>
    </div>

    {{-- CUERPO --}}
    <div class="space-y-2 text-sm text-gray-700">

        {{-- Edad + Sexo --}}
        <p>
            <span class="font-semibold">Edad:</span>
            {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : 'No disponible' }}
        </p>

        <p>
            <span class="font-semibold">Sexo:</span>
            {{ $paciente->sexo ?? 'No especificado' }}
        </p>

        {{-- NUHSA --}}
        <p>
            <span class="font-semibold">NUHSA:</span>
            {{ $paciente->nuhsa ?? '-' }}
        </p>

        {{-- Peso / Altura --}}
        <p>
            <span class="font-semibold">Peso:</span>
            {{ $paciente->peso ? $paciente->peso . ' kg' : 'No disponible' }}
        </p>

        <p>
            <span class="font-semibold">Altura:</span>
            {{ $paciente->altura ? $paciente->altura . ' cm' : 'No disponible' }}
        </p>

        {{-- IMC con accessor --}}
        @if(!is_null($paciente->imc))
            <p>
                <span class="font-semibold">IMC:</span>

                @php $cat = $paciente->imc_categoria; @endphp

                <span class="
                        px-2 py-1 rounded-full text-xs
                        @if($cat === 'Normal') bg-green-100 text-green-700
                        @elseif($cat === 'Sobrepeso') bg-yellow-100 text-yellow-700
                        @elseif(is_string($cat) && str_starts_with($cat, 'Obesidad')) bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700
                        @endif
                    ">
                    {{ number_format((float) $paciente->imc, 1, '.', '') }} — {{ $cat ?? 'No clasificado' }}
                </span>
            </p>
        @else
            <p>
                <span class="font-semibold">IMC:</span>
                <span class="text-gray-500">No disponible</span>
            </p>
        @endif

        {{-- Ver ficha completa --}}
        <div class="pt-2">
            <a href="{{ route('pacientes.show', $paciente->id) }}"
                class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                Ver ficha completa →
            </a>
        </div>
    </div>
</div>