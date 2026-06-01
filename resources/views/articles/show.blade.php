@extends('layouts.app')

@section('title', $article->title)

@section('content')

    <div class="navigation">
        <a href="{{ route('articles.index') }}" class="btn-back">← Torna all'elenco articoli</a>
    </div>

    <article>
        <h1 class="article-title">{{ $article->title }}</h1>

        <div class="article-meta">
            Pubblicato il: {{ $article->created_at}}
        </div>

        <div class="article-meta">
            Categoria: {{ $article->category?->name ?? 'nessuna' }}
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="tag-list">
                @foreach ($article->tags as $tag)
                    <x-tag-badge :tag="$tag" />
                @endforeach
            </div>
        @endif

        @if ($article->image_url)
            <div class="article-image">
                <img src="{{ $article->image_url }}" alt="Immagine di {{ $article->title }}" style="max-width: 100%; height: 350px; object-fit: cover;">
            </div>
        @endif

        <div class="article-content">
            {{ $article->content }}
        </div>
    </article>

    {{-- Modifica/Elimina solo se l'utente loggato è il proprietario dell'articolo.
         (È solo estetica: la protezione vera è nel middleware lato server.) --}}
    @auth
        @if (auth()->id() === $article->user_id)
            <div class="footer-actions">
                <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">Modifica questo articolo</x-button>
                <x-delete-form
                    :action="route('admin.articles.destroy', $article->id)"
                    confirm="Sei sicuro di voler eliminare questo articolo definitivamente?" />
            </div>
        @endif
    @endauth

@endsection
