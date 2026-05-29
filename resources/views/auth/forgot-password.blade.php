@extends('layouts.app')

@section('title', 'Password dimenticata')

@section('content')
    <div class="auth-page">
        <x-card>
            <h1>Password dimenticata?</h1>

            <p class="intro-text">
                Inserisci la tua email e ti invieremo un link per reimpostare la password.
            </p>

            @if (session('status'))
                <x-alert type="success">{{ session('status') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <x-form-field
                    name="email"
                    label="Email"
                    type="email"
                    required
                    autofocus
                />

                <div class="form-actions" style="justify-content: flex-end;">
                    <x-button variant="primary">Invia link di reset</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
