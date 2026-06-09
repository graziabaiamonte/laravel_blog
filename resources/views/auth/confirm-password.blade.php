@extends('layouts.app')

@section('title', 'Conferma password')

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">Conferma password</h1>

            <p class="mb-5 text-sm leading-relaxed text-muted">
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

                <div class="mt-6 flex justify-end gap-3">
                    <x-button variant="primary">Conferma</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
