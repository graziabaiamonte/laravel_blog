@extends('layouts.app')

@section('title', 'Modifica Articolo')

@section('content')

    @php
        $group = 'mb-4 flex flex-col gap-1.5';
        $label = 'text-sm font-medium text-ink';
        $control = 'w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
        $pill = 'inline-flex cursor-pointer select-none items-center gap-2 rounded-full border border-line bg-surface px-3 py-1.5 font-medium text-muted transition hover:border-primary hover:text-ink has-[:checked]:border-primary has-[:checked]:bg-primary has-[:checked]:text-white';
    @endphp

    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Modifica l'articolo: {{ $article->title }}</h1>
    </div>

    <x-form-errors />

    @php
        // Lista degli ID dei tag attualmente associati all'articolo,
        // utile per pre-selezionare i checkbox (con fallback su old() in caso di errore di validazione)
        $selectedTagIds = old('tags', $article->tags->pluck('id')->toArray());
    @endphp

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="{{ $group }}">
            <label for="title" class="{{ $label }}">Titolo dell'articolo</label>
            <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="category_id" class="{{ $label }}">Categoria (opzionale)</label>
            <select id="category_id" name="category_id" class="{{ $control }}">
                <option value="">— Nessuna —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="{{ $group }}">
            <label class="{{ $label }}">Tag (opzionale)</label>
            @if ($tags->isEmpty())
                <p class="text-meta text-muted">
                    Nessun tag disponibile. <a href="{{ route('admin.tags.create') }}" class="text-primary underline">Creane uno</a>.
                </p>
            @else
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($tags as $tag)
                        <label class="{{ $pill }}">
                            <input
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tag->id }}"
                                class="h-4 w-4 rounded text-primary focus:ring-primary"
                                {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}>
                            #{{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="{{ $group }}">
            <label for="image" class="{{ $label }}">Immagine</label>

            {{-- Anteprima dell'immagine attualmente salvata --}}
            @if ($article->cover_url)
                <div class="mb-2">
                    <img src="{{ $article->cover_url }}" alt="Immagine di {{ $article->title }}" class="h-auto max-w-50">
                </div>

                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded text-primary focus:ring-primary" {{ old('remove_image') ? 'checked' : '' }}>
                    Rimuovi l'immagine attuale
                </label>
            @endif

            <input type="file" id="image" name="image" accept="image/*" class="mt-2 block text-sm text-muted">
        </div>

        <div class="{{ $group }}">
            <label for="content" class="{{ $label }}">Contenuto</label>
            <textarea id="content" name="content" rows="10" class="{{ $control }} min-h-37.5 resize-y">{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="flex gap-2">
            <x-button variant="edit">Aggiorna Articolo</x-button>
            <x-button variant="cancel" :href="route('articles.show', $article->id)">Annulla</x-button>
        </div>
    </form>

@endsection
