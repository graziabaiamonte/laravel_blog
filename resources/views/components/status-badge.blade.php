@props([
    'status', // un enum App\Enums\ArticleStatus
    'id' => null, // opzionale: id dell'articolo. Serve per agganciare l'AJAX al badge giusto.
])

{{-- Classe diversa a seconda dello stato: verde = pubblicato, giallo = bozza --}}
@php
    $class = $status === \App\Enums\ArticleStatus::Published
        ? 'status-badge status-badge--published'
        : 'status-badge status-badge--draft';
@endphp

{{-- data-status-badge="<id>" permette al JavaScript di trovare QUESTO badge
     dopo aver cambiato lo stato, e di aggiornarne testo e classe al volo. --}}
<span class="{{ $class }}" @if ($id) data-status-badge="{{ $id }}" @endif>{{ $status->label() }}</span>
