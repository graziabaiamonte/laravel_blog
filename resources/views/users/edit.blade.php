@extends('layouts.app')

@section('title', 'Modifica Utente')

@section('content')

    <div class="page-header">
        <h1>Modifica utente: {{ $user->name }}</h1>
    </div>

    <x-form-errors />

    {{-- @method('PUT') perché la rotta users.update risponde in PUT/PATCH,
         ma i form HTML sanno inviare solo GET e POST: Laravel "simula" il PUT
         tramite questo campo nascosto. --}}
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome</label>
            {{-- old('name', $user->name): se la validazione fallisce ripropone
                 il valore appena digitato, altrimenti il valore attuale dell'utente. --}}
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <x-button variant="edit">Aggiorna utente</x-button>
        <x-button variant="cancel" :href="route('admin.users.index')">Annulla</x-button>
    </form>

@endsection
