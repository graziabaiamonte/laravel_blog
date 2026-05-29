@extends('layouts.app')

@section('title', 'Modifica Tag')

@section('content')

    <div class="page-header">
        <h1>Modifica tag: #{{ $tag->name }}</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('tags.update', $tag->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome tag</label>
            <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}" required>
        </div>

        <x-button variant="edit">Aggiorna tag</x-button>
        <x-button variant="cancel" :href="route('tags.index')">Annulla</x-button>
    </form>

@endsection
