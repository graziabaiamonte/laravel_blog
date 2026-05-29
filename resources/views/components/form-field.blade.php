@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'errorBag' => null,
])

@php
    $errors = $errorBag ? $errors->{$errorBag} : $errors;
    $fieldErrors = $errors->get($name);
@endphp

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    @if ($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            {{ $attributes }}
        >
    @endif

    @if ($fieldErrors)
        @foreach ($fieldErrors as $message)
            <small class="field-error">{{ $message }}</small>
        @endforeach
    @endif
</div>
