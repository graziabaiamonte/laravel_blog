@extends('layouts.app')

@section('title', 'Gestione Tag')

@section('content')

    <div class="page-header">
        <h1 class="header-title">Gestione Tag</h1>
        <x-button variant="primary" :href="route('admin.tags.create')">+ Nuovo Tag</x-button>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if ($tags->isEmpty())
        <div class="empty-state">
            <p>Nessun tag presente.</p>
        </div>
    @else
        @foreach ($tags as $tag)
            <x-card>
                <h2>#{{ $tag->name }}</h2>
                <div class="card-meta">
                    Articoli associati: {{ $tag->articles()->count() }}
                </div>
                <div class="card-actions">
                    <x-button variant="read" :href="route('admin.tags.show', $tag->id)">Dettagli</x-button>
                    <x-button variant="edit" :href="route('admin.tags.edit', $tag->id)">Modifica</x-button>
                    <x-delete-form
                        :action="route('admin.tags.destroy', $tag->id)"
                        confirm="Eliminare il tag? L'associazione con gli articoli verrà rimossa, ma gli articoli resteranno." />
                </div>
            </x-card>
        @endforeach
    @endif

    <p style="margin-top:18px"><a href="{{ route('articles.index') }}" class="btn-back">← Torna agli articoli</a></p>

@endsection
