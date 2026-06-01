@extends('layouts.app')

@section('title', '#' . $tag->name)

@section('content')

    <div class="navigation">
        <a href="{{ route('admin.tags.index') }}" class="btn-back">← Torna ai tag</a>
    </div>

    <h1 class="article-title">#{{ $tag->name }}</h1>
    <div class="article-meta">
        Articoli con questo tag: {{ $tag->articles->count() }}
    </div>

    @if ($tag->articles->isEmpty())
        <div class="empty-state">
            <p>Nessun articolo associato a questo tag.</p>
        </div>
    @else
        @foreach ($tag->articles as $article)
            <x-card>
                <h2>{{ $article->title }}</h2>
                <div class="card-meta">
                    Pubblicato il: {{ $article->created_at }}
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
        <x-button variant="edit" :href="route('admin.tags.edit', $tag->id)">Modifica tag</x-button>
        <x-delete-form
            :action="route('admin.tags.destroy', $tag->id)"
            confirm="Eliminare il tag? L'associazione con gli articoli verrà rimossa, ma gli articoli resteranno." />
    </div>

@endsection
