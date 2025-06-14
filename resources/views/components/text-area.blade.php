@props(['name', 'value' => '', 'placeholder' => '', 'rows' => 3, 'cols' => 50])

<textarea name="{{ $name }}" placeholder="{{ $placeholder }}" class="form-control">
    {{ $value }}
</textarea>
