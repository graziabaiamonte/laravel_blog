@props([
    'comment',
    'canModerate' => false,
])

{{-- Card di un singolo commento. Se è in attesa, bordo tratteggiato + badge. --}}
<div class="mb-4 rounded-card border border-line bg-surface p-4 {{ $comment->isPending() ? 'border-dashed' : '' }}">
    <div class="mb-1 flex flex-wrap items-center gap-2">
        <span class="text-sm font-semibold text-ink">{{ $comment->user->name }}</span>
        <span class="text-meta text-muted">· {{ $comment->created_at }}</span>

        @if ($comment->isPending())
            <span class="inline-block rounded-full bg-yellow-100 px-2.5 py-0.5 text-[0.7rem] font-semibold uppercase tracking-wide text-yellow-800">In attesa</span>
        @endif
    </div>

    <p class="whitespace-pre-line text-body text-ink">{{ $comment->body }}</p>

    {{-- Pulsanti di moderazione: solo per chi può moderare e solo se è in attesa. --}}
    @if ($canModerate && $comment->isPending())
        <div class="mt-3 flex gap-2 border-t border-line pt-3">
            <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}">
                @csrf
                @method('PATCH')
                <x-button variant="primary">Approva</x-button>
            </form>

            <x-delete-form
                :action="route('admin.comments.destroy', $comment->id)"
                confirm="Eliminare questo commento? L'autore riceverà una notifica via email."
                label="Elimina" />
        </div>
    @endif
</div>
