@extends('layouts.app')

@section('title', 'Verifica email')

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">Verifica la tua email</h1>

            <p class="mb-5 text-sm leading-relaxed text-muted">
                Grazie per esserti registrato! Prima di iniziare, conferma la tua email
                cliccando sul link che ti abbiamo appena inviato. Se non hai ricevuto la mail,
                possiamo rimandartene una.
            </p>

            @if (session('status') === 'verification-link-sent')
                <x-alert type="success">
                    Un nuovo link di verifica è stato inviato alla tua email.
                </x-alert>
            @endif

            <div class="mt-6 flex items-center justify-between gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-button variant="primary">Reinvia email di verifica</x-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button variant="cancel" type="submit">Esci</x-button>
                </form>
            </div>
        </x-card>
    </div>
@endsection
