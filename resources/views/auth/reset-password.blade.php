@extends('layouts.app')

@section('title', 'Reimposta password')

@section('content')
    <div class="auth-page">
        <x-card>
            <h1>Reimposta password</h1>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-form-field
                    name="email"
                    label="Email"
                    type="email"
                    :value="$request->email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <x-form-field
                    name="password"
                    label="Nuova Password"
                    type="password"
                    required
                    autocomplete="new-password"
                />

                <x-form-field
                    name="password_confirmation"
                    label="Conferma Password"
                    type="password"
                    required
                    autocomplete="new-password"
                />

                <div class="form-actions" style="justify-content: flex-end;">
                    <x-button variant="primary">Reimposta password</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
