@props(['label', 'value' => null])
<div class="mb-3">
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="text-gray-800 text-sm border border-gray-200 rounded px-3 py-2 bg-gray-50">
        {{ $value ?? $slot }}
    </div>
</div>
