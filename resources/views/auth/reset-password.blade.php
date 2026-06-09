@extends('layouts.app')

@section('title', 'Reimposta password')

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">Reimposta password</h1>

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

                <div class="mt-6 flex justify-end gap-3">
                    <x-button variant="primary">Reimposta password</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
