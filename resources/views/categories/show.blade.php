@extends('layouts.app')

@section('title', $category->name)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}" class="inline-block rounded-md px-2 py-1 text-sm font-medium text-muted no-underline transition hover:text-ink">← Torna alle categorie</a>
    </div>

    <h1 class="mb-2 text-article-title font-bold text-ink">{{ $category->name }}</h1>

    @if ($category->image_url)
        <div class="my-4">
            <img src="{{ $category->image_url }}" alt="Immagine di {{ $category->name }}" class="h-auto max-w-75">
        </div>
    @endif

    <div class="mb-6 text-meta text-muted">
        Articoli in questa categoria: {{ $category->articles->count() }}
    </div>

    @if ($category->articles->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>Nessun articolo in questa categoria.</p>
        </div>
    @else
        @foreach ($category->articles as $article)
            <x-card>
                <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $article->title }}</h2>
                <div class="mb-3 text-meta text-muted">
                    Pubblicato il: {{ $article->created_at->format('d/m/Y H:i') }}
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
        <x-button variant="edit" :href="route('admin.categories.edit', $category->id)">Modifica categoria</x-button>
        <x-delete-form
            :action="route('admin.categories.destroy', $category->id)"
            confirm="Eliminare la categoria? Gli articoli verranno impostati a nessuna categoria." />
    </div>

@endsection
