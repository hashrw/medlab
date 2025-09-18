@props([
    'name',
    'placeholder' => '',
    'rows' => 4,
    'cols' => null,
])

<textarea
    name="{{ $name }}"
    placeholder="{{ $placeholder }}"
    rows="{{ $rows }}"
    @if($cols) cols="{{ $cols }}" @endif
    {{ $attributes->merge([
        'class' => 'border border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50'
    ]) }}
>{{ $slot }}</textarea>
