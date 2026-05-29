@extends('layouts.app')

@section('title', 'Profilo')

@section('content')
    <div class="page-header">
        <h1 class="header-title">Il mio profilo</h1>
    </div>

    <div style="max-width: 640px; margin: 0 auto;">
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