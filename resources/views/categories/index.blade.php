@extends('layouts.app')

@section('title', 'Gestione Categorie')

@section('content')

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Gestione Categorie</h1>
        <x-button variant="primary" :href="route('admin.categories.create')">+ Nuova Categoria</x-button>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if ($categories->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>Nessuna categoria presente.</p>
        </div>
    @else
        @foreach ($categories as $category)
            <x-card>
                @if ($category->image_url)
                    <img src="{{ $category->image_url }}" alt="Immagine di {{ $category->name }}" class="h-auto max-w-30">
                @endif
                <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $category->name }}</h2>
                <div class="mb-3 text-meta text-muted">
                    Articoli: {{ $category->articles()->count() }}
                </div>
                <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                    <x-button variant="read" :href="route('admin.categories.show', $category->id)">Dettagli</x-button>
                    <x-button variant="edit" :href="route('admin.categories.edit', $category->id)">Modifica</x-button>
                    <x-delete-form
                        :action="route('admin.categories.destroy', $category->id)"
                        confirm="Eliminare la categoria? Gli articoli verranno impostati a nessuna categoria." />
                </div>
            </x-card>
        @endforeach
    @endif

    <p class="mt-5"><a href="{{ route('articles.index') }}" class="text-sm font-medium text-muted no-underline transition hover:text-ink">← Torna agli articoli</a></p>

@endsection
