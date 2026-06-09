@extends('layouts.app')

@section('title', $article->title)

@section('content')

    <div class="mb-4">
        <a href="{{ route('articles.index') }}"
           class="inline-block rounded-md px-2 py-1 text-sm font-medium text-muted no-underline transition hover:text-ink">← Torna all'elenco articoli</a>
    </div>

    <article>
        <h1 class="mb-2 text-article-title font-bold text-ink">{{ $article->title }}</h1>

        <div class="text-meta text-muted">
            Pubblicato il: {{ $article->created_at}}
        </div>

        <div class="mb-6 text-meta text-muted">
            Categoria: {{ $article->category?->name ?? 'nessuna' }}
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="my-2 flex flex-wrap gap-1.5">
                @foreach ($article->tags as $tag)
                    <x-tag-badge :tag="$tag" />
                @endforeach
            </div>
        @endif

        @if ($article->cover_url)
            <div class="my-6">
                <img src="{{ $article->cover_url }}" alt="Immagine di {{ $article->title }}"
                     class="h-87.5 w-full object-cover">
            </div>
        @endif

        <div class="text-body text-ink">
            {{ $article->content }}
        </div>
    </article>

    {{-- Modifica/Elimina solo se l'utente loggato è il proprietario dell'articolo.
         (È solo estetica: la protezione vera è nel middleware lato server.) --}}
    @auth
        @if (auth()->id() === $article->user_id)
            <div class="mt-6 flex gap-2 border-t border-line pt-6">
                <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">Modifica questo articolo</x-button>
                <x-delete-form
                    :action="route('admin.articles.destroy', $article->id)"
                    confirm="Sei sicuro di voler eliminare questo articolo definitivamente?" />
            </div>
        @endif
    @endauth

    {{-- ============================ COMMENTI ============================ --}}
    <section id="commenti" class="mt-10 border-t border-line pt-8">
        <h2 class="mb-6 text-subheading font-semibold text-ink">Commenti</h2>

        {{-- Il form si mostra SOLO se l'articolo è pubblicato: sulle bozze
             (visibili solo a proprietario/admin) non si può commentare, quindi
             nemmeno mostriamo il form. --}}
        {{-- Messaggio di esito (riempito dal JS via AJAX; in fallback no-JS
             mostra il flash di sessione). Parte nascosto se non c'è nulla. --}}
        <div
            data-comment-feedback
            class="mb-4 rounded-card border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 {{ session('success') ? '' : 'hidden' }}">
            {{ session('success') }}
        </div>

        {{-- Il form si mostra SOLO se l'articolo è pubblicato: sulle bozze
             (visibili solo a proprietario/admin) non si può commentare, quindi
             nemmeno mostriamo il form. --}}
        @if ($article->isPublished())
            {{-- Form: solo per utenti loggati --}}
            @auth
                {{-- data-comment-form: hook per il JS che intercetta l'invio.
                     Senza JS, il form fa un normale POST + redirect (fallback). --}}
                <form method="POST" action="{{ route('comments.store', $article->id) }}" class="mb-8" data-comment-form>
                    @csrf
                    <div class="mb-3 flex flex-col gap-1.5">
                        <label for="comment-body" class="text-sm font-medium text-ink">Lascia un commento</label>
                        <textarea
                            id="comment-body"
                            name="body"
                            rows="3"
                            required
                            placeholder="Scrivi qui il tuo commento..."
                            class="w-full resize-y rounded-md border bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10 @error('body') border-danger @else border-line @enderror">{{ old('body') }}</textarea>
                        {{-- Box errore: il JS ci scrive l'errore di validazione AJAX;
                             in fallback no-JS mostra l'errore del FormRequest. --}}
                        <small data-comment-error class="text-xs text-danger">@error('body'){{ $message }}@enderror</small>
                    </div>
                    <x-button variant="primary">Invia commento</x-button>
                </form>
            @else
                <p class="mb-8 text-meta text-muted">
                    <a href="{{ route('login') }}" class="text-primary underline">Accedi</a> per lasciare un commento.
                </p>
            @endauth
        @else
            <p class="mb-8 text-meta text-muted">
                I commenti saranno disponibili quando l’articolo sarà pubblicato.
            </p>
        @endif

        {{-- Lista commenti: approvati per tutti; in attesa solo per chi può
             moderare oppure per chi l'ha scritto (vede il proprio "in attesa"). --}}
        @php
            $visibleComments = $article->comments->filter(function ($c) use ($canModerate) {
                return $c->isApproved()
                    || $canModerate
                    || (auth()->check() && auth()->id() === $c->user_id);
            });
        @endphp

        {{-- data-comments-list: il JS inserisce qui in cima i nuovi commenti. --}}
        <div data-comments-list>
            @forelse ($visibleComments as $comment)
                <x-comment :comment="$comment" :canModerate="$canModerate" />
            @empty
                {{-- Sulle bozze non si può commentare: niente invito a commentare.
                     data-comments-empty: il JS rimuove questa riga al primo commento. --}}
                @if ($article->isPublished())
                    <p data-comments-empty class="text-meta text-muted">Ancora nessun commento. Sii il primo a commentare!</p>
                @endif
            @endforelse
        </div>
    </section>

@endsection
