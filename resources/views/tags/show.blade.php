@extends('layouts.app')

@section('title', '#' . $tag->name)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.tags.index') }}" class="inline-block rounded-md px-2 py-1 text-sm font-medium text-muted no-underline transition hover:text-ink">← Torna ai tag</a>
    </div>

    <h1 class="mb-2 text-article-title font-bold text-ink">#{{ $tag->name }}</h1>
    <div class="mb-6 text-meta text-muted">
        Articoli con questo tag: {{ $tag->articles->count() }}
    </div>

    @if ($tag->articles->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>Nessun articolo associato a questo tag.</p>
        </div>
    @else
        @foreach ($tag->articles as $article)
            <x-card>
                <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $article->title }}</h2>
                <div class="mb-3 text-meta text-muted">
                    Pubblicato il: {{ $article->created_at }}
                </div>
                <div>
                    {{ \Illuminate\Support\Str::limit($article->content, 150) }}
                </div>
                <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                    <x-button variant="read" :href="route('articles.show', $article->id)">Leggi tutto</x-button>
                </div>
            </x-card>
        @endforeach
    @endif

    <div class="mt-6 flex gap-2 border-t border-line pt-6">
        <x-button variant="edit" :href="route('admin.tags.edit', $tag->id)">Modifica tag</x-button>
        <x-delete-form
            :action="route('admin.tags.destroy', $tag->id)"
            confirm="Eliminare il tag? L'associazione con gli articoli verrà rimossa, ma gli articoli resteranno." />
    </div>

@endsection
