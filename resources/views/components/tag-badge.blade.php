@props([
    'tag',
    'linked' => true,
])

@if ($linked)
    <a href="{{ route('admin.tags.show', $tag->id) }}" class="tag-badge">#{{ $tag->name }}</a>
@else
    <span class="tag-badge">#{{ $tag->name }}</span>
@endif
