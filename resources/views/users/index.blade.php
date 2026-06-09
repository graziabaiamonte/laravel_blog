@extends('layouts.app')

@section('title', 'Gestione Utenti')

@section('content')

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Gestione Utenti</h1>
    </div>

    {{-- ⚠️ Pagina didattica: qui qualsiasi utente loggato può gestire gli altri.
         In futuro sarà riservata agli amministratori. --}}

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if ($users->isEmpty())
        <div class="rounded-card border border-dashed border-line bg-white px-6 py-12 text-center text-muted">
            <p>Nessun utente presente.</p>
        </div>
    @else
        @foreach ($users as $user)
            <x-card>
                <h2 class="mb-2 text-subheading font-semibold text-ink">{{ $user->name }}</h2>
                <div class="mb-3 text-meta text-muted">
                    Email: {{ $user->email }}
                    &middot; Articoli: {{ $user->articles()->count() }}
                </div>
                <div class="mt-4 flex flex-wrap gap-2 border-t border-line pt-4">
                    <x-button variant="edit" :href="route('admin.users.edit', $user->id)">Modifica</x-button>

                    {{-- Non mostriamo il pulsante "Elimina" sul nostro stesso account:
                         per cancellare il proprio profilo si usa la pagina del profilo. --}}
                    @if ($user->id !== auth()->id())
                        <x-delete-form
                            :action="route('admin.users.destroy', $user->id)"
                            confirm="Eliminare definitivamente l'utente {{ $user->name }}?" />
                    @endif
                </div>
            </x-card>
        @endforeach
    @endif

    <p class="mt-5"><a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-muted no-underline transition hover:text-ink">← Torna alla dashboard</a></p>

@endsection
