<x-medico-layout>
    <x-slot name="header">
        <nav class="font-semibold text-xl text-gray-800 leading-tight" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('pacientes.index') }}">Pacientes</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0
                                 l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256
                                 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667
                                 c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372
                                 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-500">Editar {{ $paciente->user?->name }}</span>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg">

                <div class="px-6 py-4 font-semibold text-lg bg-blue-800 text-white rounded-t-lg">
                    Editar paciente
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('pacientes.update', $paciente->id) }}">
                        @csrf
                        @method('PUT')

                        @include('pacientes._form', ['paciente' => $paciente])
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-medico-layout>
