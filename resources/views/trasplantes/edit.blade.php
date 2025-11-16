<x-medico-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Trasplante
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg p-6">

                <h3 class="text-lg font-semibold text-blue-800 mb-4">
                    Modificar datos del trasplante
                </h3>

                <form method="POST" action="{{ route('trasplantes.update', $trasplante->id) }}">
                    @method('PUT')
                    @include('trasplantes._form')
                </form>
            </div>
        </div>
    </div>
</x-medico-layout>
