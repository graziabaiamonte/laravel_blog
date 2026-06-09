@extends('layouts.app')

@section('title', 'Nuova Categoria')

@section('content')

    @php
        $group = 'mb-4 flex flex-col gap-1.5';
        $label = 'text-sm font-medium text-ink';
        $control = 'w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10';
    @endphp

    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Nuova categoria</h1>
    </div>

    <x-form-errors />

    {{-- enctype="multipart/form-data" è OBBLIGATORIO per inviare file tramite form. --}}
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="{{ $group }}">
            <label for="name" class="{{ $label }}">Nome categoria</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Es. Tecnologia" required class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="image" class="{{ $label }}">Immagine (opzionale)</label>
            <input type="file" id="image" name="image" accept="image/*" class="text-sm text-muted">
        </div>

        <div class="flex gap-2">
            <x-button variant="primary">Crea categoria</x-button>
            <x-button variant="cancel" :href="route('admin.categories.index')">Annulla</x-button>
        </div>
    </form>

@endsection
