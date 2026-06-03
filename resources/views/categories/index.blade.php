@extends('layouts.app')

@section('title', 'Gestione Categorie')

@section('content')

    <div class="page-header">
        <h1 class="header-title">Gestione Categorie</h1>
        <x-button variant="primary" :href="route('admin.categories.create')">+ Nuova Categoria</x-button>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if ($categories->isEmpty())
        <div class="empty-state">
            <p>Nessuna categoria presente.</p>
        </div>
    @else
        @foreach ($categories as $category)
            <x-card>
                @if ($category->image_url)
                    <img src="{{ $category->image_url }}" alt="Immagine di {{ $category->name }}" style="max-width: 120px; height: auto;">
                @endif
                <h2>{{ $category->name }}</h2>
                <div class="card-meta">
                    Articoli: {{ $category->articles()->count() }}
                </div>
                <div class="card-actions">
                    <x-button variant="read" :href="route('admin.categories.show', $category->id)">Dettagli</x-button>
                    <x-button variant="edit" :href="route('admin.categories.edit', $category->id)">Modifica</x-button>
                    <x-delete-form
                        :action="route('admin.categories.destroy', $category->id)"
                        confirm="Eliminare la categoria? Gli articoli verranno impostati a nessuna categoria." />
                </div>
            </x-card>
        @endforeach
    @endif

    <p style="margin-top:18px"><a href="{{ route('articles.index') }}" class="btn-back">← Torna agli articoli</a></p>

@endsection
