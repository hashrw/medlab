<x-medico-layout>

    <div class="py-3 px-4">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

                {{-- CABECERA --}}
                <div class="p-4 bg-blue-700 text-white flex justify-between items-center rounded-t-lg">
                    <h3 class="text-lg font-semibold tracking-wide">
                        Información del medicamento
                    </h3>

                    <a href="{{ route('medicamentos.index') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow transition">
                        Volver al listado
                    </a>
                </div>

                {{-- CUERPO --}}
                <div class="p-6 space-y-6">

                    {{-- Nombre --}}
                    <div class="flex items-start gap-3">
                        <i class="fas fa-capsules text-blue-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">
                                Nombre común
                            </p>

                            <p class="text-xl font-semibold text-gray-800">
                                {{ $medicamento->nombre }}
                            </p>
                        </div>
                    </div>

                    {{-- Dosis --}}
                    <div class="flex items-start gap-3">
                        <i class="fas fa-prescription-bottle text-blue-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">
                                Dosis (mg)
                            </p>

                            <p class="text-xl font-semibold text-gray-800">
                                {{ $medicamento->miligramos }} mg
                            </p>
                        </div>
                    </div>

                </div>

                {{-- PIE --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('medicamentos.index') }}"
                        class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded shadow transition">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-medico-layout>