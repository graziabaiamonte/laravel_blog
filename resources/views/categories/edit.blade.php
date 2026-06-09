@extends('layouts.app')

@section('title', 'Modifica Categoria')

@section('content')

    @php
        $group = 'mb-4 flex flex-col gap-1.5';
        $label = 'text-sm font-medium text-ink';
        $control = 'w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
    @endphp

    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Modifica categoria: {{ $category->name }}</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="{{ $group }}">
            <label for="name" class="{{ $label }}">Nome categoria</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="image" class="{{ $label }}">Immagine</label>

            {{-- Anteprima dell'immagine attualmente salvata --}}
            @if ($category->image_url)
                <div class="mb-2">
                    <img src="{{ $category->image_url }}" alt="Immagine di {{ $category->name }}" class="h-auto max-w-50">
                </div>

                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="remove_image" value="1" class="h-4 w-4 rounded text-primary focus:ring-primary" {{ old('remove_image') ? 'checked' : '' }}>
                    Rimuovi l'immagine attuale
                </label>
            @endif

            <input type="file" id="image" name="image" accept="image/*" class="mt-2 block text-sm text-muted">
        </div>

        <div class="flex gap-2">
            <x-button variant="edit">Aggiorna categoria</x-button>
            <x-button variant="cancel" :href="route('admin.categories.index')">Annulla</x-button>
        </div>
    </form>

@endsection
