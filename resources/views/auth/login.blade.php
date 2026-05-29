@extends('layouts.app')

@section('title', 'Accedi')

@section('content')
    <div class="auth-page">
        <x-card>
            <h1>Accedi</h1>

            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <x-form-field
                    name="email"
                    label="Email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <x-form-field
                    name="password"
                    label="Password"
                    type="password"
                    required
                    autocomplete="current-password"
                />

                <div class="form-group">
                    <label style="font-weight: normal; flex-direction: row; align-items: center; gap: 6px; display: inline-flex;">
                        <input type="checkbox" name="remember">
                        <span>Ricordami</span>
                    </label>
                </div>

                <div class="form-actions">
                    @if (Route::has('password.request'))
                        <a class="text-link" href="{{ route('password.request') }}">
                            Password dimenticata?
                        </a>
                    @endif

                    <x-button variant="primary">Accedi</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
