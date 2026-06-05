@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1 class="header-title">Dashboard</h1>
        <x-button variant="primary" :href="route('admin.articles.create')">+ Nuovo Articolo</x-button>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Card di benvenuto --}}
    <x-card>
        <p>Sei loggato come <strong>{{ Auth::user()->name }}</strong>.</p>

        <div class="card-actions">
            <x-button variant="primary" :href="route('home')">Vai al blog</x-button>
            <x-button variant="cancel" :href="route('admin.profile.edit')">Modifica profilo</x-button>
            <x-button variant="cancel" :href="route('admin.users.index')">Gestione utenti</x-button>
        </div>
    </x-card>

    {{-- Griglia a due colonne: si impila in una sola colonna su schermi piccoli
         (grid-cols-1) e diventa a due colonne da medi in su (md:grid-cols-2). --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="margin-top:24px">

        {{-- COLONNA 1: gli articoli dell'utente loggato.
             $articles arriva da ArticleController@index, filtrati con ownedBy().
             Essendo tutti suoi, mostriamo sempre Modifica/Elimina. --}}
        <div>
            <h2>I miei articoli</h2>

            @if ($articles->isEmpty())
                <div class="empty-state">
                    <p>Non hai ancora scritto nessun articolo.</p>
                </div>
            @else
                @foreach ($articles as $article)
                    <x-card>
                        <h2>{{ $article->title }}</h2>
                        {{-- Badge di stato: l'autore vede subito se la sua roba
                             è ancora in bozza o è stata pubblicata dall'admin. --}}
                        <x-status-badge :status="$article->status" />
                        <div class="card-meta">
                            Creato il: {{ $article->created_at }}
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
                            {{ $article->excerpt }}
                        </div>
                        {{-- Dropdown bozza/pubblicato: SOLO l'admin (chi ha il
                             permesso 'publish articles') lo vede. L'autore no. --}}
                        @can('publish articles')
                            <x-status-form :article="$article" />
                        @endcan
                        <div class="card-actions">
                            <x-button variant="read" :href="route('articles.show', $article->id)">Leggi tutto</x-button>
                            <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">Modifica</x-button>
                            <x-delete-form
                                :action="route('admin.articles.destroy', $article->id)"
                                confirm="Sei sicuro di voler eliminare questo articolo definitivamente?" />
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
                <h2>Articoli degli altri utenti</h2>

                @if (empty($othersArticles) || $othersArticles->isEmpty())
                    <div class="empty-state">
                        <p>Non ci sono articoli di altri utenti.</p>
                    </div>
                @else
                    @foreach ($othersArticles as $article)
                        <x-card>
                            <h2>{{ $article->title }}</h2>
                            {{-- Badge di stato anche qui: l'admin vede a colpo
                                 d'occhio quali bozze deve ancora pubblicare. --}}
                            <x-status-badge :status="$article->status" />
                            <div class="card-meta">
                                {{-- $article->user?->name: il ? evita errori se l'autore
                                     fosse mancante (es. utente eliminato). --}}
                                Autore: <strong>{{ $article->user?->name ?? 'sconosciuto' }}</strong>
                                &middot; Creato il: {{ $article->created_at }}
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
                            <div class="card-actions">
                                <x-button variant="read" :href="route('articles.show', $article->id)">Leggi tutto</x-button>
                                <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">Modifica</x-button>
                                <x-delete-form
                                    :action="route('admin.articles.destroy', $article->id)"
                                    confirm="Sei sicuro di voler eliminare questo articolo di {{ $article->user?->name ?? 'un altro utente' }}?" />
                            </div>
                        </x-card>
                    @endforeach
                @endif
            </div>
        @endcan

    </div>
@endsection
