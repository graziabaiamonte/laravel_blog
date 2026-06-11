@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Dashboard</h1>
        <x-button variant="primary" :href="route('admin.articles.create')">+ {{ __('New Article') }}</x-button>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Card di benvenuto --}}
    <x-card>
        <p>{{ __('You are logged in as') }} <strong>{{ Auth::user()->name }}</strong>.</p>

        <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
            <x-button variant="primary" :href="route('home')">{{ __('Go to blog') }}</x-button>
            <x-button variant="cancel" :href="route('admin.profile.edit')">{{ __('Edit profile') }}</x-button>
            <x-button variant="cancel" :href="route('admin.users.index')">{{ __('User management') }}</x-button>
        </div>
    </x-card>

    {{-- Griglia a due colonne: si impila in una sola colonna su schermi piccoli
         (grid-cols-1) e diventa a due colonne da medi in su (md:grid-cols-2). --}}
    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- COLONNA 1: gli articoli dell'utente loggato.
             $articles arriva da ArticleController@index, filtrati con ownedBy().
             Essendo tutti suoi, mostriamo sempre Modifica/Elimina. --}}
        <div>
            <h2 class="mb-4 text-subheading font-semibold text-ink">{{ __('My articles') }}</h2>

            @if ($articles->isEmpty())
                <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
                    <p>{{ __("You haven't written any articles yet.") }}</p>
                </div>
            @else
                @foreach ($articles as $article)
                    <x-card>
                        <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $article->title }}</h2>
                        {{-- Badge di stato: l'autore vede subito se la sua roba
                             è ancora in bozza o è stata pubblicata dall'admin. --}}
                        <x-status-badge :status="$article->status" :id="$article->id" />
                        <div class="mb-3 text-meta text-muted">
                            {{ __('Created on:') }} {{ $article->created_at }}
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
                            {{ $article->excerpt }}
                        </div>
                        {{-- Dropdown bozza/pubblicato: SOLO l'admin (chi ha il
                             permesso 'publish articles') lo vede. L'autore no. --}}
                        @can('publish articles')
                            <x-status-form :article="$article" />
                        @endcan
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                            <x-button variant="read" :href="route('articles.show', $article->id)">{{ __('Read more') }}</x-button>
                            <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">{{ __('Edit') }}</x-button>
                            <x-delete-form
                                :action="route('admin.articles.destroy', $article->id)"
                                :confirm="__('Are you sure you want to permanently delete this article?')" />
                        </div>
                    </x-card>
                @endforeach
            @endif
        </div>

        {{-- COLONNA 2: gli articoli degli ALTRI utenti.
             @can('manage articles') mostra questo blocco SOLO a chi ha quel
             permesso (l'admin). Per un author l'intera colonna sparisce. --}}
        @can('manage articles')
            <div>
                <h2 class="mb-4 text-subheading font-semibold text-ink">{{ __("Other users' articles") }}</h2>

                @if (empty($othersArticles) || $othersArticles->isEmpty())
                    <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
                        <p>{{ __('There are no articles from other users.') }}</p>
                    </div>
                @else
                    @foreach ($othersArticles as $article)
                        <x-card>
                            <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $article->title }}</h2>
                            {{-- Badge di stato anche qui: l'admin vede a colpo
                                 d'occhio quali bozze deve ancora pubblicare. --}}
                            <x-status-badge :status="$article->status" :id="$article->id" />
                            <div class="mb-3 text-meta text-muted">
                                {{-- $article->user?->name: il ? evita errori se l'autore
                                     fosse mancante (es. utente eliminato). --}}
                                {{ __('Author:') }} <strong>{{ $article->user?->name ?? __('unknown') }}</strong>
                                &middot; {{ __('Created on:') }} {{ $article->created_at }}
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
                                {{ $article->excerpt }}
                            </div>
                            {{-- Dropdown bozza/pubblicato sugli articoli altrui:
                                 l'admin ha 'publish articles', quindi lo vede. --}}
                            @can('publish articles')
                                <x-status-form :article="$article" />
                            @endcan
                            {{-- Stessi pulsanti: per l'admin le rotte edit/update/destroy
                                 funzionano anche su articoli altrui grazie al middleware
                                 owns.article (che lascia passare chi ha 'manage articles'). --}}
                            <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                                <x-button variant="read" :href="route('articles.show', $article->id)">{{ __('Read more') }}</x-button>
                                <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">{{ __('Edit') }}</x-button>
                                <x-delete-form
                                    :action="route('admin.articles.destroy', $article->id)"
                                    :confirm="__('Are you sure you want to delete this article by :name?', ['name' => $article->user?->name ?? __('another user')])" />
                            </div>
                        </x-card>
                    @endforeach
                @endif
            </div>
        @endcan

    </div>
@endsection
