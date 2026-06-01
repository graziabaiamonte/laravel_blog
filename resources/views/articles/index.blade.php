@extends('layouts.app')

@section('title', 'Blog')

@section('content')

    <div class="page-header">
        <h1 class="header-title">Blog</h1>
        <div style="display:flex; gap:10px;">
            <x-button variant="cancel" :href="route('admin.tags.index')">Tag</x-button>
            <x-button variant="cancel" :href="route('admin.categories.index')">Categorie</x-button>
            
            {{-- Il pulsante per creare un articolo si mostra solo agli utenti loggati. --}}
            @auth
                <x-button variant="primary" :href="route('admin.articles.create')">+ Nuovo Articolo</x-button>
            @endauth
        </div>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <form method="GET" action="{{ route('articles.index') }}" class="filters-form">
        <div class="form-group">
            <label for="filter-search">Ricerca</label>
            <input
                type="text"
                id="filter-search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Titolo o contenuto..."
                maxlength="100">
        </div>

        <div class="form-group">
            <label for="filter-category">Categoria</label>
            <select id="filter-category" name="category_id">
                <option value="">Tutte le categorie</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="filter-tag">Tag</label>
            <select id="filter-tag" name="tag_id">
                <option value="">Tutti i tag</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected(request('tag_id') == $tag->id)>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filters-actions">
            <x-button variant="primary">Filtra</x-button>
            @if (request()->hasAny(['search', 'category_id', 'tag_id']))
                <x-button variant="cancel" :href="route('articles.index')">Reset</x-button>
            @endif
        </div>
    </form>

    @if ($articles->isEmpty())
        <div class="empty-state">
            <p>Non ci sono ancora articoli.</p>
        </div>
    @else
        @foreach ($articles as $article)
            <x-card>
                <h2>{{ $article->title }}</h2>
                <div class="card-meta">
                    {{-- Pubblicato il: {{ $article->created_at->format('d/m/Y') }} --}}
                    Pubblicato il: {{ $article->created_at }}

                    &middot; Scritto da: {{ $article->user->name }}
                    &middot; Categoria: {{ $article->category?->name ?? 'nessuna' }}
                </div>
                @if ($article->tags->isNotEmpty())
                    <div class="tag-list">
                        @foreach ($article->tags as $tag)
                            <x-tag-badge :tag="$tag" />
                        @endforeach
                    </div>
                @endif
                <div>
                    {{-- {{ \Illuminate\Support\Str::limit($article->content, 150) }} --}}
                    {{ $article->excerpt }}
                </div>
                <div class="card-actions">
                    {{-- Home pubblica: qui si può SOLO leggere l'articolo.
                         I pulsanti Modifica/Elimina sono stati spostati nella dashboard
                         (dove ogni utente gestisce i propri articoli). --}}
                    <x-button variant="read" :href="route('articles.show', $article->id)">Leggi tutto</x-button>
                </div>
            </x-card>
        @endforeach
    @endif

@endsection
