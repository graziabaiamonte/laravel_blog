@extends('layouts.app')

@section('title', 'Gestione Tag')

@section('content')

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Gestione Tag</h1>
        <x-button variant="primary" :href="route('admin.tags.create')">+ Nuovo Tag</x-button>
    </div>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if ($tags->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>Nessun tag presente.</p>
        </div>
    @else
        @foreach ($tags as $tag)
            <x-card>
                <h2 class="mb-2 text-subheading font-semibold text-ink">#{{ $tag->name }}</h2>
                <div class="mb-3 text-meta text-muted">
                    Articoli associati: {{ $tag->articles()->count() }}
                </div>
                <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                    <x-button variant="read" :href="route('admin.tags.show', $tag->id)">Dettagli</x-button>
                    <x-button variant="edit" :href="route('admin.tags.edit', $tag->id)">Modifica</x-button>
                    <x-delete-form
                        :action="route('admin.tags.destroy', $tag->id)"
                        confirm="Eliminare il tag? L'associazione con gli articoli verrà rimossa, ma gli articoli resteranno." />
                </div>
            </x-card>
        @endforeach
    @endif

    <p class="mt-5"><a href="{{ route('articles.index') }}" class="text-sm font-medium text-muted no-underline transition hover:text-ink">← Torna agli articoli</a></p>

@endsection
