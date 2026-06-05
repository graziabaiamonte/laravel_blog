@props([
    'status', // un enum App\Enums\ArticleStatus
])

{{-- Classe diversa a seconda dello stato: verde = pubblicato, giallo = bozza --}}
@php
    $class = $status === \App\Enums\ArticleStatus::Published
        ? 'status-badge status-badge--published'
        : 'status-badge status-badge--draft';
@endphp

<span class="{{ $class }}">{{ $status->label() }}</span>
