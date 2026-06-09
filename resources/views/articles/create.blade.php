@extends('layouts.app')

@section('title', 'Crea Articolo')

@section('content')

    @php
        // Classi riutilizzabili per i campi del form (utility Tailwind).
        $group = 'mb-4 flex flex-col gap-1.5';
        $label = 'text-sm font-medium text-ink';
        $control = 'w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
      
        $pill = 'inline-flex cursor-pointer select-none items-center gap-2 rounded-full border border-line bg-surface px-3 py-1.5 font-medium text-muted transition hover:border-primary hover:text-ink has-[:checked]:border-primary has-[:checked]:bg-primary has-[:checked]:text-white';
    @endphp

    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Scrivi un nuovo articolo</h1>
    </div>

    <x-form-errors />

    {{-- enctype="multipart/form-data" è OBBLIGATORIO per inviare file tramite form. --}}
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="{{ $group }}">
            <label for="title" class="{{ $label }}">Titolo dell'articolo</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Inserisci un titolo..." class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="category_id" class="{{ $label }}">Categoria (opzionale)</label>
            <select id="category_id" name="category_id" class="{{ $control }}">
                <option value="">— Nessuna —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            #{{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="{{ $group }}">
            <label for="image" class="{{ $label }}">Immagine (opzionale)</label>
            {{-- accept="image/*" suggerisce al browser di mostrare solo immagini nel selettore,
                 ma la validazione VERA resta lato server nella regola ImageFile. --}}
            <input type="file" id="image" name="image" accept="image/*" class="text-sm text-muted">
        </div>

        <div class="{{ $group }}">
            <label for="content" class="{{ $label }}">Contenuto</label>
            <textarea id="content" name="content" rows="10" placeholder="Inizia a scrivere il tuo articolo qui..." class="{{ $control }} min-h-37.5 resize-y">{{ old('content') }}</textarea>
        </div>

        <div class="flex gap-2">
            <x-button variant="primary">Pubblica Articolo</x-button>
            <x-button variant="cancel" :href="route('articles.index')">Annulla</x-button>
        </div>
    </form>

@endsection
