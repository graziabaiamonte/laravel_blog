@extends('layouts.app')

@section('title', 'Profilo')

@section('content')
    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Il mio profilo</h1>
    </div>

    <div class="mx-auto max-w-160">
        <x-card>
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card>
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card>
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
@endsection
