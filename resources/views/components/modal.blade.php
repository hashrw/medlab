@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    'paciente' // Añadimos la propiedad paciente
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <!-- Fondo oscuro -->
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <!-- Contenedor del modal -->
    <div
        x-show="show"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <!-- Encabezado del modal -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold">Datos Clínicos de {{ $paciente->user->name }}</h2>
            <button x-on:click="show = false" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Contenido del modal -->
        <div class="p-6 space-y-4">
            <!-- Edad -->
            <div>
                <x-input-label :value="__('Edad')" />
                <x-text-input readonly disabled class="block mt-1 w-full" :value="$paciente->edad . ' años'" />
            </div>

            <!-- Enfermedades -->
            <div>
                <x-input-label :value="__('Enfermedades')" />
                <ul class="mt-1">
                    @forelse ($paciente->enfermedads as $enfermedad)
                        <li>{{ $enfermedad->nombre }}</li>
                    @empty
                        <li>{{ __('No hay enfermedades registradas.') }}</li>
                    @endforelse
                </ul>
            </div>

            <!-- Tratamientos -->
            <div>
                <x-input-label :value="__('Tratamientos')" />
                <ul class="mt-1">
                    @forelse ($paciente->tratamientos as $tratamiento)
                        <li>{{ $tratamiento->nombre }} ({{ $tratamiento->descripcion }})</li>
                    @empty
                        <li>{{ __('No hay tratamientos registrados.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Pie del modal -->
        <div class="px-6 py-4 bg-gray-100 text-right">
            <x-danger-button x-on:click="show = false">
                {{ __('Cerrar') }}
            </x-danger-button>
        </div>
    </div>
</div>