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

@php
    $control = 'w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
@endphp

<div class="mb-4 flex flex-col gap-1.5">
    @if ($label)
        <label for="{{ $name }}" class="text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    @if ($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $attributes->merge(['class' => $control . ' min-h-[150px] resize-y']) }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => $control]) }}
        >
    @endif

    @if ($fieldErrors)
        @foreach ($fieldErrors as $message)
            <small class="mt-0.5 text-xs text-danger">{{ $message }}</small>
        @endforeach
    @endif
</div>
