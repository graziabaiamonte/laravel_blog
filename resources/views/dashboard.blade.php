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

    <h2 style="margin-top:24px">I miei articoli</h2>

    {{-- Qui mostriamo SOLO gli articoli dell'utente loggato.
         La variabile $articles arriva da ArticleController@index, che li filtra
         con lo scope ownedBy(). Essendo tutti suoi, mostriamo sempre
         i pulsanti Modifica/Elimina (oltre a Leggi tutto). --}}
    @if ($articles->isEmpty())
        <div class="empty-state">
            <p>Non hai ancora scritto nessun articolo.</p>
        </div>
    @else
        @foreach ($articles as $article)
            <x-card>
                <h2>{{ $article->title }}</h2>
                <div class="card-meta">
                    Pubblicato il: {{ $article->created_at }}
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
@endsection
