@extends('layouts.app')

@section('title', 'Registrati')

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">Crea un account</h1>

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

                <div class="mt-6 flex items-center justify-between gap-3">
                    <a class="text-sm text-muted underline hover:text-ink" href="{{ route('login') }}">
                        Hai già un account?
                    </a>

                    <x-button variant="primary">Registrati</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
