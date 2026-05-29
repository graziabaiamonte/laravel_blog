@extends('layouts.app')

@section('title', 'Conferma password')

@section('content')
    <div class="auth-page">
        <x-card>
            <h1>Conferma password</h1>

            <p class="intro-text">
                Questa è un'area riservata. Conferma la tua password per continuare.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <x-form-field
                    name="password"
                    label="Password"
                    type="password"
                    required
                    autocomplete="current-password"
                />

                <div class="form-actions" style="justify-content: flex-end;">
                    <x-button variant="primary">Conferma</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
