@extends('layouts.app')

@section('title', 'Modifica Categoria')

@section('content')

    <div class="page-header">
        <h1>Modifica categoria: {{ $category->name }}</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome categoria</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="form-group">
            <label for="image">Immagine</label>

            {{-- Anteprima dell'immagine attualmente salvata --}}
            @if ($category->image_url)
                <div class="current-image">
                    <img src="{{ $category->image_url }}" alt="Immagine di {{ $category->name }}" style="max-width: 200px; height: auto;">
                </div>

                <label>
                    <input type="checkbox" name="remove_image" value="1" {{ old('remove_image') ? 'checked' : '' }}>
                    Rimuovi l'immagine attuale
                </label>
            @endif

            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <x-button variant="edit">Aggiorna categoria</x-button>
        <x-button variant="cancel" :href="route('admin.categories.index')">Annulla</x-button>
    </form>

@endsection
