<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo trasplante
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">

                <div class="px-6 py-4 bg-blue-800 text-white">
                    <h3 class="text-lg font-semibold">Registro de trasplante</h3>
                    <p class="text-xs text-blue-100 mt-1">
                        Introduzca la información del procedimiento.
                    </p>
                </div>

                <div class="p-8 space-y-6 text-gray-800">
                    <form method="POST" action="{{ route('trasplantes.store') }}">
                        @include('trasplantes._form')
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-medico-layout>
