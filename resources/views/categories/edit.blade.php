@extends('layouts.app')

@section('title', 'Modifica Categoria')

@section('content')

    <div class="page-header">
        <h1>Modifica categoria: {{ $category->name }}</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome categoria</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        </div>

        <x-button variant="edit">Aggiorna categoria</x-button>
        <x-button variant="cancel" :href="route('admin.categories.index')">Annulla</x-button>
    </form>

@endsection
