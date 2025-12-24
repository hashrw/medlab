<x-medico-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
            Inferir diagnóstico
        </h3>
    </x-slot>

    <div class="mt-4 bg-white shadow-md rounded-lg border border-gray-200 p-4">
        <form method="GET" action="{{ route('diagnosticos.inferirSelector') }}" class="flex gap-3">
            <input type="text" name="q" value="{{ $q ?? '' }}"
                   class="w-full border border-gray-300 rounded-md p-2"
                   placeholder="Buscar por ID, NUHSA o nombre">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                Buscar
            </button>
        </form>
    </div>

    <div class="mt-4 bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 font-semibold">
            Pacientes
        </div>

        <div class="p-4">
            @forelse($pacientes as $p)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                    <div class="text-sm text-gray-800">
                       {{ $p->usuarioAcceso->name ?? ('Paciente #' . $p->id) }}
                        @if(isset($p->nuhsa) && $p->nuhsa)
                            <span class="text-gray-500">({{ $p->nuhsa }})</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('diagnosticos.inferir', $p->id) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md">
                            Inferir
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-sm text-gray-500">Sin resultados.</div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $pacientes->links() }}
        </div>
    </div>
</x-medico-layout>
