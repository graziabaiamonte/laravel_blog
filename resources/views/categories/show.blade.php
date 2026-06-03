@extends('layouts.app')

@section('title', $category->name)

@section('content')

    <div class="navigation">
        <a href="{{ route('admin.categories.index') }}" class="btn-back">← Torna alle categorie</a>
    </div>

    <h1 class="article-title">{{ $category->name }}</h1>

    @if ($category->image_url)
        <div class="current-image">
            <img src="{{ $category->image_url }}" alt="Immagine di {{ $category->name }}" style="max-width: 300px; height: auto;">
        </div>
    @endif

    <div class="article-meta">
        Articoli in questa categoria: {{ $category->articles->count() }}
    </div>

    @if ($category->articles->isEmpty())
        <div class="empty-state">
            <p>Nessun articolo in questa categoria.</p>
        </div>
    @else
        @foreach ($category->articles as $article)
            <x-card>
                <h2>{{ $article->title }}</h2>
                <div class="card-meta">
                    Pubblicato il: {{ $article->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    {{ \Illuminate\Support\Str::limit($article->content, 150) }}
                </div>
                <div class="card-actions">
                    <x-button variant="read" :href="route('articles.show', $article->id)">Leggi tutto</x-button>
                </div>
            </x-card>
        @endforeach
    @endif

    <div class="footer-actions">
        <x-button variant="edit" :href="route('admin.categories.edit', $category->id)">Modifica categoria</x-button>
        <x-delete-form
            :action="route('admin.categories.destroy', $category->id)"
            confirm="Eliminare la categoria? Gli articoli verranno impostati a nessuna categoria." />
    </div>

@endsection
