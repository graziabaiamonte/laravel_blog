@extends('layouts.app')

@section('title', 'Registrati')

@section('content')
    <div class="auth-page">
        <x-card>
            <h1>Crea un account</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-form-field
                    name="name"
                    label="Nome"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                />

                <x-form-field
                    name="email"
                    label="Email"
                    type="email"
                    required
                    autocomplete="username"
                />

                <x-form-field
                    name="password"
                    label="Password"
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

                <div class="form-actions">
                    <a class="text-link" href="{{ route('login') }}">
                        Hai già un account?
                    </a>

                    <x-button variant="primary">Registrati</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
