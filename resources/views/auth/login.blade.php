@extends('layouts.app')

@section('title', 'Accedi')

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">Accedi</h1>

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

                <div class="mb-4">
                    <label class="inline-flex cursor-pointer items-center gap-1.5 font-normal text-ink">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded text-primary focus:ring-primary">
                        <span>Ricordami</span>
                    </label>
                </div>

                <div class="mt-6 flex items-center justify-between gap-3">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-muted underline hover:text-ink" href="{{ route('password.request') }}">
                            Password dimenticata?
                        </a>
                    @endif

                    <x-button variant="primary">Accedi</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
