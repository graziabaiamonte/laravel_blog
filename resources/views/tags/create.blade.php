@extends('layouts.app')

@section('title', 'Nuovo Tag')

@section('content')

    <div class="page-header">
        <h1>Nuovo tag</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('admin.tags.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nome tag</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Es. laravel" required>
        </div>

        <x-button variant="primary">Crea tag</x-button>
        <x-button variant="cancel" :href="route('admin.tags.index')">Annulla</x-button>
    </form>

@endsection
