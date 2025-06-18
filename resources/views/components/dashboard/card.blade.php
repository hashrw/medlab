@props(['titulo', 'ruta'])

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">{{ $titulo }}</h2>
    <a href="{{ $ruta }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Ver todos
    </a>
</div>
