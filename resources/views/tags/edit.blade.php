@extends('layouts.app')

@section('title', 'Modifica Tag')

@section('content')

    <div class="mb-6 border-b border-line p-6">
        <h1 class="text-heading font-bold text-ink">Modifica tag: #{{ $tag->name }}</h1>
    </div>

    <x-form-errors />

    <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4 flex flex-col gap-1.5">
            <label for="name" class="text-sm font-medium text-ink">Nome tag</label>
            <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}" required
                   class="w-full rounded-md border border-line bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10">
        </div>

        <div class="flex gap-2">
            <x-button variant="edit">Aggiorna tag</x-button>
            <x-button variant="cancel" :href="route('admin.tags.index')">Annulla</x-button>
        </div>
    </form>

@endsection
