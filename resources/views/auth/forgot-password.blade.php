@extends('layouts.app')

@section('title', 'Password dimenticata')

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">Password dimenticata?</h1>

            <p class="mb-5 text-sm leading-relaxed text-muted">
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

                <div class="mt-6 flex justify-end gap-3">
                    <x-button variant="primary">Invia link di reset</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
