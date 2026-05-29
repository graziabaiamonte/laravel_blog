@extends('layouts.app')

@section('title', 'Gestione Utenti')

@section('content')

    <div class="page-header">
        <h1 class="header-title">Gestione Utenti</h1>
    </div>

    {{-- ⚠️ Pagina didattica: qui qualsiasi utente loggato può gestire gli altri.
         In futuro sarà riservata agli amministratori. --}}

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if ($users->isEmpty())
        <div class="empty-state">
            <p>Nessun utente presente.</p>
        </div>
    @else
        @foreach ($users as $user)
            <x-card>
                <h2>{{ $user->name }}</h2>
                <div class="card-meta">
                    Email: {{ $user->email }}
                    &middot; Articoli: {{ $user->articles()->count() }}
                </div>
                <div class="card-actions">
                    <x-button variant="edit" :href="route('users.edit', $user->id)">Modifica</x-button>

                    {{-- Non mostriamo il pulsante "Elimina" sul nostro stesso account:
                         per cancellare il proprio profilo si usa la pagina del profilo. --}}
                    @if ($user->id !== auth()->id())
                        <x-delete-form
                            :action="route('users.destroy', $user->id)"
                            confirm="Eliminare definitivamente l'utente {{ $user->name }}?" />
                    @endif
                </div>
            </x-card>
        @endforeach
    @endif

    <p style="margin-top:18px"><a href="{{ route('dashboard') }}" class="btn-back">← Torna alla dashboard</a></p>

@endsection
