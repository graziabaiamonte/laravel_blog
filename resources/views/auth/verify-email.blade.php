@extends('layouts.app')

@section('title', __('Verify Email'))

@section('content')
    <div class="mx-auto my-12 max-w-md px-4">
        <x-card>
            <h1 class="mb-6 text-center text-subheading font-semibold text-ink">{{ __('Verify your email') }}</h1>

            <p class="mb-5 text-sm leading-relaxed text-muted">
                {{ __('Thanks for signing up! Before getting started, please confirm your email by clicking the link we just sent you. If you didn\'t receive the email, we can send you another.') }}
            </p>

            @if (session('status') === 'verification-link-sent')
                <x-alert type="success">
                    {{ __('A new verification link has been sent to your email address.') }}
                </x-alert>
            @endif

            <div class="mt-6 flex items-center justify-between gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-button variant="primary">{{ __('Resend Verification Email') }}</x-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button variant="cancel" type="submit">{{ __('Log Out') }}</x-button>
                </form>
            </div>
        </x-card>
    </div>
@endsection
