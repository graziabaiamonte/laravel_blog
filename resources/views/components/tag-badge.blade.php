@props([
    'tag',
    'linked' => true,
])

@php
    $badge = 'inline-block rounded-full bg-indigo-50 px-2.5 py-1 text-badge font-medium text-indigo-700';
@endphp

@if ($linked)
    <a href="{{ route('admin.tags.show', $tag->id) }}" class="{{ $badge }}">#{{ $tag->name }}</a>
@else
    <span class="{{ $badge }}">#{{ $tag->name }}</span>
@endif
