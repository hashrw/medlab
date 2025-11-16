@props(['paciente'])

<div class="bg-white border rounded-xl shadow hover:shadow-lg transition p-5">

    {{-- CABECERA --}}
    <div class="flex justify-between items-center border-b pb-2 mb-3">
        <h4 class="font-semibold text-blue-800">
            {{ $paciente->nombre }}
        </h4>

        <div class="flex space-x-3 text-gray-600">

            <a href="{{ route('pacientes.show', $paciente->id) }}"
               class="hover:text-blue-600" title="Ver ficha">
                <i class="fas fa-eye"></i>
            </a>

            <a href="{{ route('pacientes.edit', $paciente->id) }}"
               class="hover:text-yellow-600" title="Editar">
                <i class="fas fa-edit"></i>
            </a>

            <form method="POST" action="{{ route('pacientes.destroy', $paciente->id) }}"
                  onsubmit="return confirm('¿Eliminar este paciente?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="hover:text-red-600" title="Eliminar">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>

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
        @if($paciente->imc)
            <p>
                <span class="font-semibold">IMC:</span>

                <span class="
                    px-2 py-1 rounded-full text-xs
                    @if($paciente->imc_categoria == 'Normal') bg-green-100 text-green-700
                    @elseif($paciente->imc_categoria == 'Sobrepeso') bg-yellow-100 text-yellow-700
                    @elseif(str_starts_with($paciente->imc_categoria, 'Obesidad')) bg-red-100 text-red-700
                    @endif
                ">
                    {{ $paciente->imc }} — {{ $paciente->imc_categoria }}
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
