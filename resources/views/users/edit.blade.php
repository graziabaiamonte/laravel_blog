@extends('layouts.app')

@section('title', 'Modifica Utente')

@section('content')

    @php
        $group = 'mb-4 flex flex-col gap-1.5';
        $label = 'text-sm font-medium text-ink';
        $control = 'w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10 disabled:cursor-not-allowed disabled:bg-canvas disabled:text-muted';
    @endphp

    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Modifica utente: {{ $user->name }}</h1>
    </div>

    <x-form-errors />

    {{-- @method('PUT') perché la rotta users.update risponde in PUT/PATCH,
         ma i form HTML sanno inviare solo GET e POST: Laravel "simula" il PUT
         tramite questo campo nascosto. --}}
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="{{ $group }}">
            <label for="name" class="{{ $label }}">Nome</label>
            {{-- old('name', $user->name): se la validazione fallisce ripropone
                 il valore appena digitato, altrimenti il valore attuale dell'utente. --}}
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="{{ $control }}">
        </div>

        <div class="{{ $group }}">
            <label for="email" class="{{ $label }}">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $control }}">
        </div>

        {{-- $isSelf: l'admin sta modificando il proprio account?
             $currentRole: il ruolo attuale dell'utente (getRoleNames() torna una
             collection di nomi; first() prende il primo, o null se non ne ha). --}}
        @php
            $isSelf = $user->id === Auth::id();
            $currentRole = old('role', $user->getRoleNames()->first());
        @endphp

        <div class="{{ $group }}">
            <label for="role" class="{{ $label }}">Ruolo</label>
            {{-- @disabled($isSelf): se modifichi te stesso il select è bloccato e
                 NON viene inviato, così non puoi cambiarti il ruolo da solo. --}}
            <select id="role" name="role" class="{{ $control }}" @disabled($isSelf) @if (! $isSelf) required @endif>
                @foreach ($roles as $role)
                    {{-- @selected(...) mette "selected" sull'opzione che corrisponde
                         al ruolo attuale dell'utente. --}}
                    <option value="{{ $role->name }}" @selected($currentRole === $role->name)>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            @if ($isSelf)
                <small class="text-muted">Non puoi modificare il tuo stesso ruolo.</small>
            @endif
        </div>

        <div class="flex gap-2">
            <x-button variant="edit">Aggiorna utente</x-button>
            <x-button variant="cancel" :href="route('admin.users.index')">Annulla</x-button>
        </div>
    </form>

@endsection
