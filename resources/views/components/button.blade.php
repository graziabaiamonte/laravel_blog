@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'submit',
])

@php
    // Stile comune a tutti i bottoni (forma, testo, transizione).
    $base = 'inline-block rounded-md text-sm font-medium no-underline cursor-pointer transition active:translate-y-px';

    // Ogni variante porta i propri colori E il proprio padding
    // (così px/py non vanno mai in conflitto tra base e variante).
    $variants = [
        'primary' => 'px-4 py-2 bg-primary text-white hover:bg-primary-hover',
        'cancel'  => 'px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300',
        'edit'    => 'px-4 py-2 bg-edit text-white hover:bg-edit-hover',
        'danger'  => 'px-4 py-2 bg-danger text-white hover:bg-danger-hover',
        'read'    => 'px-4 py-2 bg-read text-white hover:bg-read-hover',
        'back'    => 'px-2 py-1 bg-transparent text-muted hover:text-ink',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
