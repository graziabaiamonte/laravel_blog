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

@endsection
