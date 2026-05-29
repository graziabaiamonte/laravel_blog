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

    <form action="{{ route('articles.update', $article->id) }}" method="POST">
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
                    Nessun tag disponibile. <a href="{{ route('tags.create') }}">Creane uno</a>.
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
            <label for="content">Contenuto</label>
            <textarea id="content" name="content" rows="10">{{ old('content', $article->content) }}</textarea>
        </div>

        <x-button variant="edit">Aggiorna Articolo</x-button>
        <x-button variant="cancel" :href="route('articles.show', $article->id)">Annulla</x-button>
    </form>

@endsection
