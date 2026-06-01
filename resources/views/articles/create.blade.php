@extends('layouts.app')

@section('title', 'Crea Articolo')

@section('content')

    <div class="page-header">
        <h1>Scrivi un nuovo articolo</h1>
    </div>

    <x-form-errors />

    {{-- enctype="multipart/form-data" è OBBLIGATORIO per inviare file tramite form. --}}
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Titolo dell'articolo</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Inserisci un titolo...">
        </div>

        <div class="form-group">
            <label for="category_id">Categoria (opzionale)</label>
            <select id="category_id" name="category_id">
                <option value="">— Nessuna —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            #{{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="image">Immagine (opzionale)</label>
            {{-- accept="image/*" suggerisce al browser di mostrare solo immagini nel selettore,
                 ma la validazione VERA resta lato server nella regola ImageFile. --}}
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="content">Contenuto</label>
            <textarea id="content" name="content" rows="10" placeholder="Inizia a scrivere il tuo articolo qui...">{{ old('content') }}</textarea>
        </div>

        <x-button variant="primary">Pubblica Articolo</x-button>
        <x-button variant="cancel" :href="route('articles.index')">Annulla</x-button>
    </form>

@endsection
