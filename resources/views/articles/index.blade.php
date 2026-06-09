@extends('layouts.app')

@section('title', 'Blog')

@section('content')

    @php
        // Classi riutilizzabili per i campi del form filtri (utility Tailwind).
        $group = 'flex flex-col gap-1.5';
        $label = 'text-sm font-medium text-ink';
        $control = 'rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
    @endphp

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Blog</h1>
        <div class="flex gap-2.5">
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

    <form method="GET" action="{{ route('articles.index') }}"
          class="mb-6 flex flex-wrap items-end gap-4 rounded-card border border-line bg-white p-4">
        <div class="{{ $group }}">
            <label for="filter-search" class="{{ $label }}">Ricerca</label>
            <input
                type="text"
                id="filter-search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Titolo o contenuto..."
                maxlength="100"
                class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="filter-category" class="{{ $label }}">Categoria</label>
            <select id="filter-category" name="category_id" class="{{ $control }}">
                <option value="">Tutte le categorie</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="{{ $group }}">
            <label for="filter-tag" class="{{ $label }}">Tag</label>
            <select id="filter-tag" name="tag_id" class="{{ $control }}">
                <option value="">Tutti i tag</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected(request('tag_id') == $tag->id)>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <x-button variant="primary">Filtra</x-button>
            @if (request()->hasAny(['search', 'category_id', 'tag_id']))
                <x-button variant="cancel" :href="route('articles.index')">Reset</x-button>
            @endif
        </div>
    </form>

    @if ($articles->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>Non ci sono ancora articoli.</p>
        </div>
    @else
        @foreach ($articles as $article)
            <x-card>
                <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $article->title }}</h2>
                <div class="mb-3 text-meta text-muted">
                    {{-- Pubblicato il: {{ $article->created_at->format('d/m/Y') }} --}}
                    Pubblicato il: {{ $article->created_at }}

                    &middot; Scritto da: {{ $article->user->name }}
                    &middot; Categoria: {{ $article->category?->name ?? 'nessuna' }}
                </div>
                @if ($article->tags->isNotEmpty())
                    <div class="my-2 flex flex-wrap gap-1.5">
                        @foreach ($article->tags as $tag)
                            <x-tag-badge :tag="$tag" />
                        @endforeach
                    </div>
                @endif
                <div>
                    {{-- {{ \Illuminate\Support\Str::limit($article->content, 150) }} --}}
                    {{ $article->excerpt }}
                </div>
                <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                    {{-- Home pubblica: qui si può SOLO leggere l'articolo.
                         I pulsanti Modifica/Elimina sono stati spostati nella dashboard
                         (dove ogni utente gestisce i propri articoli). --}}
                    <x-button variant="read" :href="route('articles.show', $article->id)">Leggi tutto</x-button>
                </div>
            </x-card>
        @endforeach
    @endif

@endsection
