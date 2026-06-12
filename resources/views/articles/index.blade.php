@extends('layouts.app')

@section('title', 'Blog')

@section('content')

    @php
        // Classi riutilizzabili per i campi del form filtri (utility Tailwind).
        // Su mobile ogni campo va a tutta larghezza (w-full) e si impila;
        // da `sm` in su torna a larghezza automatica (sm:w-auto).
        $group = 'flex w-full flex-col gap-1.5 sm:w-auto';
        $label = 'text-sm font-medium text-ink';
        $control = 'rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
        // Stessa base dei campi, ma con padding destro extra (pr-10) per non far
        // finire il testo sotto la freccia del select, e una larghezza minima.
        $select = $control.' pr-10 sm:min-w-[12rem]';
    @endphp

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Blog</h1>
        <div class="flex gap-2.5">
            <x-button variant="cancel" :href="route('admin.tags.index')">{{ __('Tags') }}</x-button>
            <x-button variant="cancel" :href="route('admin.categories.index')">{{ __('Categories') }}</x-button>

            {{-- Il pulsante per creare un articolo si mostra solo agli utenti loggati. --}}
            @auth
                <x-button variant="primary" :href="route('admin.articles.create')">+ {{ __('New Article') }}</x-button>
            @endauth
        </div>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <form method="GET" action="{{ route('articles.index') }}"
          class="mb-6 flex flex-wrap items-end gap-4 rounded-card border border-line bg-white p-4">
        <div class="{{ $group }}">
            <label for="filter-search" class="{{ $label }}">{{ __('Search') }}</label>
            <input
                type="text"
                id="filter-search"
                name="search"
                value="{{ request('search') }}"
                placeholder="{{ __('Title or content...') }}"
                maxlength="100"
                class="{{ $control }}">
        </div>

        {{-- Categorie: checkbox multiple. name="category_id[]" → arriva come array.
             @checked tiene selezionate quelle scelte dopo il submit. --}}
        <div class="{{ $group }}">
            <span class="{{ $label }}">{{ __('Categories') }}</span>
            <div class="flex flex-wrap gap-x-4 gap-y-1.5 pt-1">
                @foreach ($categories as $category)
                    <label class="flex items-center gap-2 text-base">
                        <input
                            type="checkbox"
                            name="category_id[]"
                            value="{{ $category->id }}"
                            @checked(in_array($category->id, (array) request('category_id')))
                            class="rounded border-line text-primary focus:ring-primary/30">
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Tag: checkbox multiple in AND (l'articolo deve avere TUTTI i tag scelti). --}}
        <div class="{{ $group }}">
            <span class="{{ $label }}">{{ __('Tags') }}</span>
            <div class="flex flex-wrap gap-x-4 gap-y-1.5 pt-1">
                @foreach ($tags as $tag)
                    <label class="flex items-center gap-2 text-base">
                        <input
                            type="checkbox"
                            name="tag_id[]"
                            value="{{ $tag->id }}"
                            @checked(in_array($tag->id, (array) request('tag_id')))
                            class="rounded border-line text-primary focus:ring-primary/30">
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Filtro per data di pubblicazione: range Da/A, entrambi opzionali. --}}
        <div class="{{ $group }}">
            <label for="filter-date-from" class="{{ $label }}">{{ __('From') }}</label>
            <input
                type="date"
                id="filter-date-from"
                name="date_from"
                value="{{ request('date_from') }}"
                max="{{ request('date_to') }}"
                class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="filter-date-to" class="{{ $label }}">{{ __('To') }}</label>
            <input
                type="date"
                id="filter-date-to"
                name="date_to"
                value="{{ request('date_to') }}"
                min="{{ request('date_from') }}"
                class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="filter-sort-title" class="{{ $label }}">{{ __('Sort by title') }}</label>
            <select id="filter-sort-title" name="sort_title" class="{{ $select }}">
                <option value="">{{ __('—') }}</option>
                <option value="title" @selected(request('sort_title') === 'title')>{{ __('Title A–Z') }}</option>
                <option value="-title" @selected(request('sort_title') === '-title')>{{ __('Title Z–A') }}</option>
            </select>
        </div>

        <div class="{{ $group }}">
            <label for="filter-sort-date" class="{{ $label }}">{{ __('Sort by date') }}</label>
            <select id="filter-sort-date" name="sort_date" class="{{ $select }}">
                <option value="">{{ __('—') }}</option>
                <option value="-created_at" @selected(request('sort_date') === '-created_at')>{{ __('Newest first') }}</option>
                <option value="created_at" @selected(request('sort_date') === 'created_at')>{{ __('Oldest first') }}</option>
            </select>
        </div>

        <div class="flex gap-2">
            <x-button variant="primary">{{ __('Filter') }}</x-button>
            @if (request()->hasAny(['search', 'category_id', 'tag_id', 'date_from', 'date_to', 'sort_title', 'sort_date']))
                <x-button variant="cancel" :href="route('articles.index')">Reset</x-button>
            @endif
        </div>
    </form>

    @if ($articles->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>{{ __('There are no articles yet.') }}</p>
        </div>
    @else
        @foreach ($articles as $article)
            <x-card>
                <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $article->title }}</h2>
                <div class="mb-3 text-meta text-muted">
                    {{-- Pubblicato il: {{ $article->created_at->format('d/m/Y') }} --}}
                    {{ __('Published on:') }} {{ $article->created_at }}

                    &middot; {{ __('Written by:') }} {{ $article->user->name }}
                    &middot; {{ __('Category:') }} {{ $article->category?->name ?? __('none') }}
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
                    <x-button variant="read" :href="route('articles.show', $article->id)">{{ __('Read more') }}</x-button>
                </div>
            </x-card>
        @endforeach
    @endif

@endsection
