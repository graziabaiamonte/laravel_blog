@props([
    'status', // un enum App\Enums\ArticleStatus
    'id' => null, // opzionale: id dell'articolo. Serve per agganciare l'AJAX al badge giusto.
])

{{-- Classe diversa a seconda dello stato: verde = pubblicato, giallo = bozza --}}
@php
    $base = 'inline-block rounded-full px-2.5 py-0.5 text-[0.7rem] font-semibold uppercase tracking-wide';
    $class = $status === \App\Enums\ArticleStatus::Published
        ? $base . ' bg-green-100 text-green-800'
        : $base . ' bg-yellow-100 text-yellow-800';
@endphp

{{-- data-status-badge="<id>" permette al JavaScript di trovare QUESTO badge
     dopo aver cambiato lo stato, e di aggiornarne testo e classe al volo. --}}
<span class="{{ $class }}" @if ($id) data-status-badge="{{ $id }}" @endif>{{ $status->label() }}</span>
