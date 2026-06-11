@extends('layouts.app')

@section('title', $article->title)

@section('content')

    <div class="mb-4">
        <a href="{{ route('articles.index') }}"
           class="inline-block rounded-md px-2 py-1 text-sm font-medium text-muted no-underline transition hover:text-ink">← {{ __('Back to articles list') }}</a>
    </div>

    <article>
        <h1 class="mb-2 text-article-title font-bold text-ink">{{ $article->title }}</h1>

        <div class="text-meta text-muted">
            {{ __('Published on:') }} {{ $article->created_at}}
        </div>

        <div class="mb-6 text-meta text-muted">
            {{ __('Category:') }} {{ $article->category?->name ?? __('none') }}
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="my-2 flex flex-wrap gap-1.5">
                @foreach ($article->tags as $tag)
                    <x-tag-badge :tag="$tag" />
                @endforeach
            </div>
        @endif

        @if ($article->cover_url)
            <div class="my-6">
                <img src="{{ $article->cover_url }}" alt="{{ __('Image of') }} {{ $article->title }}"
                     class="h-87.5 w-full object-cover">
            </div>
        @endif

        <div class="text-body text-ink">
            {{ $article->content }}
        </div>
    </article>

    {{-- Modifica/Elimina solo se l'utente loggato è il proprietario dell'articolo.
         (È solo estetica: la protezione vera è nel middleware lato server.) --}}
    @auth
        @if (auth()->id() === $article->user_id)
            <div class="mt-6 flex gap-2 border-t border-line pt-6">
                <x-button variant="edit" :href="route('admin.articles.edit', $article->id)">{{ __('Edit this article') }}</x-button>
                <x-delete-form
                    :action="route('admin.articles.destroy', $article->id)"
                    :confirm="__('Are you sure you want to permanently delete this article?')" />
            </div>
        @endif
    @endauth

    {{-- ============================ COMMENTI ============================ --}}
    <section id="commenti" class="mt-10 border-t border-line pt-8">
        <h2 class="mb-6 text-subheading font-semibold text-ink">{{ __('Comments') }}</h2>

        <div
            data-comment-feedback
            class="mb-4 rounded-card border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 {{ session('success') ? '' : 'hidden' }}">
            {{ session('success') }}
        </div>

        @if ($article->isPublished())

            @auth
                {{-- data-comment-form: hook per il JS che intercetta l'invio.
                     Senza JS, il form fa un normale POST + redirect (fallback). --}}
                <form method="POST" action="{{ route('comments.store', $article->id) }}" class="mb-8" data-comment-form>
                    @csrf
                    <div class="mb-3 flex flex-col gap-1.5">
                        <label for="comment-body" class="text-sm font-medium text-ink">{{ __('Leave a comment') }}</label>
                        <textarea
                            id="comment-body"
                            name="body"
                            rows="3"
                            required
                            placeholder="{{ __('Write your comment here...') }}"
                            class="w-full resize-y rounded-md border bg-white px-3 py-2 text-base focus:border-primary focus:outline-none focus:ring focus:ring-primary/10 @error('body') border-danger @else border-line @enderror">{{ old('body') }}</textarea>
                        <small data-comment-error class="text-xs text-danger">@error('body'){{ $message }}@enderror</small>
                    </div>
                    <x-button variant="primary">{{ __('Submit comment') }}</x-button>
                </form>
            @else
                <p class="mb-8 text-meta text-muted">
                    <a href="{{ route('login') }}" class="text-primary underline">{{ __('Log in') }}</a> {{ __('to leave a comment.') }}
                </p>
            @endauth
        @else
            <p class="mb-8 text-meta text-muted">
                {{ __('Comments will be available once the article is published.') }}
            </p>
        @endif

        {{-- Lista commenti: approvati per tutti; in attesa solo per chi può
             moderare oppure per chi l'ha scritto (vede il proprio "in attesa"). --}}
        @php
            $visibleComments = $article->comments->filter(function ($c) use ($canModerate) {
                return $c->isApproved()
                    || $canModerate
                    || (auth()->check() && auth()->id() === $c->user_id);
            });
        @endphp

        {{-- data-comments-list: il JS inserisce qui in cima i nuovi commenti. --}}
        <div data-comments-list>
            @forelse ($visibleComments as $comment)
                <x-comment :comment="$comment" :canModerate="$canModerate" />
            @empty
                {{-- Sulle bozze non si può commentare: niente invito a commentare.
                     data-comments-empty: il JS rimuove questa riga al primo commento. --}}
                @if ($article->isPublished())
                    <p data-comments-empty class="text-meta text-muted">{{ __('No comments yet. Be the first to comment!') }}</p>
                @endif
            @endforelse
        </div>
    </section>

@endsection
