@extends('layouts.app')

@section('title', 'Modifica Articolo')

@section('content')

    <div class="page-header">
        <h1>Modifica l'articolo: {{ $article->title }}</h1>
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

        <div class="form-group">
            <label for="title">Titolo dell'articolo</label>
            <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}">
        </div>

        <div class="form-group">
            <label for="category_id">Categoria (opzionale)</label>
            <select id="category_id" name="category_id">
                <option value="">— Nessuna —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tag (opzionale)</label>
            @if ($tags->isEmpty())
                <p class="muted">
                    Nessun tag disponibile. <a href="{{ route('admin.tags.create') }}">Creane uno</a>.
                </p>
            @else
                <div class="checkbox-list">
                    @foreach ($tags as $tag)
                        <label>
                            <input
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tag->id }}"
                                {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}>
                            #{{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="image">Immagine</label>

            {{-- Anteprima dell'immagine attualmente salvata --}}
            @if ($article->cover_url)
                <div class="current-image">
                    <img src="{{ $article->cover_url }}" alt="Immagine di {{ $article->title }}" style="max-width: 200px; height: auto;">
                </div>

                <label>
                    <input type="checkbox" name="remove_image" value="1" {{ old('remove_image') ? 'checked' : '' }}>
                    Rimuovi l'immagine attuale
                </label>
            @endif

            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="content">Contenuto</label>
            <textarea id="content" name="content" rows="10">{{ old('content', $article->content) }}</textarea>
        </div>

        <x-button variant="edit">Aggiorna Articolo</x-button>
        <x-button variant="cancel" :href="route('articles.show', $article->id)">Annulla</x-button>
    </form>

@endsection
