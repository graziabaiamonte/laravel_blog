@props([
    'article',
])

{{-- Mini-form per cambiare lo stato di UN articolo (bozza <-> pubblicato).
     Va mostrato SOLO all'admin: il controllo @can lo facciamo in dashboard,
     dove usiamo questo componente. La rotta è comunque protetta dal middleware
     'permission:publish articles', quindi è blindata anche lato server. --}}
<form method="POST"
      action="{{ route('admin.articles.status', $article->id) }}"
      class="mt-3 flex items-center gap-2"
      data-article-id="{{ $article->id }}">
    @csrf
    {{-- La rotta è un PATCH: in HTML i form fanno solo GET/POST, quindi
         @method('PATCH') aggiunge il campo nascosto che Laravel interpreta. --}}
    @method('PATCH')

    <select name="status" aria-label="Stato dell'articolo"
            class="rounded-md border border-line bg-white px-2.5 py-1.5 text-sm">
        {{-- Una <option> per ogni caso dell'enum; @selected preseleziona
             quella corrispondente allo stato attuale dell'articolo. --}}
        @foreach (\App\Enums\ArticleStatus::cases() as $statusOption)
            <option value="{{ $statusOption->value }}"
                @selected($article->status === $statusOption)>
                {{ $statusOption->label() }}
            </option>
        @endforeach
    </select>

    <x-button variant="primary">Salva</x-button>
</form>
