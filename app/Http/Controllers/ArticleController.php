<?php
namespace App\Http\Controllers;
use App\Enums\Permission;
use App\Enums\ArticleStatus;
use App\Events\ArticlePublished;
use App\Events\ArticleDeleted;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// use App\Traits\HandlesImageUpload; // VECCHIO sistema

/**
 * GESTIONE degli articoli per gli utenti loggati
 *
 */
class ArticleController extends Controller
{

    // use HandlesImageUpload; // VECCHIO sistema

    public function index()
    {
        $user = request()->user();

        $articles = Article::with('category', 'tags')
            ->ownedBy($user)
            ->orderByDesc('created_at')
            ->get();

        $othersArticles = null;
        if ($user->can(Permission::ManageArticles->value)) {
            $othersArticles = Article::with('category', 'tags', 'user')
                ->where('user_id', '!=', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('dashboard', compact('articles', 'othersArticles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        // $validatedData = $request->validate([
        //     'title' => 'required|max:255',
        //     'content' => 'required',
        //     'category_id' => 'nullable|exists:categories,id',
        //     'tags' => 'nullable|array',
        //     'tags.*' => 'exists:tags,id',
        // ]);

        $validatedData = $request->validated();

        // $request->user() restituisce l'utente autenticato.
        // 'image' non è più una colonna: la escludiamo dai dati passati a create().
        $article = $request->user()->articles()->create(
            collect($validatedData)->except(['tags', 'image', 'remove_image'])->toArray()
        );

        // Ogni articolo nasce SEMPRE in bozza 
        $article->status = ArticleStatus::Draft;
        $article->save();

        $article->tags()->sync($validatedData['tags'] ?? []);

        // Foto principale -> collection "cover" della Media Library.
        if ($request->hasFile('image')) {
            $article->addMediaFromRequest('image')
                ->toMediaCollection(Article::MEDIA_COVER);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Articolo creato con successo!');
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Article $article)
    {
        // carica i tag attualmente associati a quell'articolo
        $article->load('tags');

        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validatedData = $request->validated();

        $dataToUpdate = $validatedData;

        // Togliamo le chiavi che NON sono colonne da aggiornare con fill():
        // 'tags' (relazione pivot), 'image' e 'remove_image' (gestiti dalla Media Library).
        unset($dataToUpdate['tags'], $dataToUpdate['image'], $dataToUpdate['remove_image']);

        $article->fill($dataToUpdate)->save();
        $article->tags()->sync($validatedData['tags'] ?? []);

        if ($request->hasFile('image')) {
            $article->addMediaFromRequest('image')
                ->toMediaCollection(Article::MEDIA_COVER);
        } elseif ($request->boolean('remove_image')) {
            $article->clearMediaCollection(Article::MEDIA_COVER);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Articolo aggiornato con successo!');
    }

    /**
     * Cambia lo STATO dell'articolo 
     */
    public function updateStatus(Request $request, Article $article)
    {
        // Validiamo che 'status' sia uno dei valori validi dell'enum
        $validated = $request->validate([
            'status' => ['required', Rule::enum(ArticleStatus::class)],
        ]);

        // Rule::enum valida la stringa; qui la trasformiamo nell'enum vero.
        $newStatus = ArticleStatus::from($validated['status']);

        $wasPublished = $article->isPublished();

        $article->status = $newStatus;
        $article->save();

        // Evento SOLO sulla transizione bozza -> pubblicato:
        // if (! $wasPublished && $article->isPublished()) {
        //    // L'utente riceve subito il JSON; l'evento (e i suoi listener lenti) gira dopo (utile ad esempio quando il listener invia email)
        //    defer(fn () => ArticlePublished::dispatch($article));
        // }

        if (! $wasPublished && $article->isPublished()) {
            ArticlePublished::dispatch($article);
        }

        $message = "Stato aggiornato: «{$article->title}» ora è {$newStatus->label()}.";

        // BIVIO sincrono / asincrono:
        // - Se la richiesta arriva via AJAX, fetch() manda l'header "Accept: application/json":
        //   wantsJson() diventa true -> rispondiamo con JSON e NESSUN ricaricamento di pagina.
        // - Altrimenti vecchio redirect
        if ($request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'status'      => $newStatus->value,    
                'label'       => $newStatus->label(),  
                'isPublished' => $article->isPublished(),
                'message'     => $message,
            ]);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Article $article)
    {
        // Catturiamo i dati PRIMA di eliminare: dopo delete() l'articolo non esiste più nel DB
        $title = $article->title;
        $deletedBy = $request->user()?->name ?? 'sconosciuto';

        $article->delete($article);

        // Avvisiamo il resto dell'app che l'articolo è stato eliminato
        ArticleDeleted::dispatch($title, $deletedBy);

        return redirect()->route('admin.dashboard')->with('success', 'Articolo eliminato con successo!');
    }
}
