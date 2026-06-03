@extends('layouts.app')

@section('title', 'Nuova Categoria')

@section('content')

    <div class="page-header">
        <h1>Nuova categoria</h1>
    </div>

    <x-form-errors />

    {{-- enctype="multipart/form-data" è OBBLIGATORIO per inviare file tramite form. --}}
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Nome categoria</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Es. Tecnologia" required>
        </div>

        <div class="form-group">
            <label for="image">Immagine (opzionale)</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <x-button variant="primary">Crea categoria</x-button>
        <x-button variant="cancel" :href="route('admin.categories.index')">Annulla</x-button>
    </form>

@endsection
