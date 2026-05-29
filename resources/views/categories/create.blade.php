@extends('layouts.app')

@section('title', 'Nuova Categoria')

@section('content')

    <div class="page-header">
        <h1>Nuova categoria</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nome categoria</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Es. Tecnologia" required>
        </div>

        <x-button variant="primary">Crea categoria</x-button>
        <x-button variant="cancel" :href="route('categories.index')">Annulla</x-button>
    </form>

@endsection
