<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
// use Illuminate\Http\Request;  // usata per leggere i dati inviati dalle form

/**
 * Controller di GESTIONE degli articoli (riservato agli utenti loggati).
 *
 * La parte pubblica (lista di tutti gli articoli + singolo articolo) è stata
 * spostata in App\Http\Controllers\Frontend\ArticleController.
 *
 * Qui restano:
 * - index:   la lista dei SOLI articoli dell'utente loggato (alimenta la dashboard)
 * - create/store/edit/update/destroy: il CRUD vero e proprio
 */
class ArticleController extends Controller
{
    /**
     * Mostra SOLO gli articoli dell'utente loggato (la sua dashboard).
     *
     * Qui sta il cuore della richiesta: usiamo lo scope ownedBy() del model
     * Article per filtrare in base all'utente autenticato. Lo scope nasconde
     * il dettaglio del "where user_id = ..." e rende la query molto leggibile.
     */
    public function index()
    {
        $articles = Article::with('category', 'tags')
            ->ownedBy(request()->user())
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard', compact('articles'));
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

        //     // Ogni id nell'array tags deve esistere nella tabella tags
        //     'tags.*' => 'exists:tags,id',
        // ]);

        $validatedData = $request->validated();

        // Creiamo l'articolo ATTRAVERSO la relazione dell'utente loggato:
        // $request->user() restituisce l'utente autenticato, e ->articles()->create(...)
        // imposta automaticamente la colonna user_id con il suo id.
        // (escludiamo i tag, che vanno nella pivot; infatti nei fillable di Article non c'è 'tags')
        $article = $request->user()->articles()->create(collect($validatedData)->except('tags')->toArray());

        // Sincronizziamo i tag selezionati nella tabella pivot article_tag.
        $article->tags()->sync($validatedData['tags'] ?? []);

        // Dopo aver creato l'articolo torniamo alla dashboard (i miei articoli).
        return redirect()->route('dashboard')->with('success', 'Articolo creato con successo!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     // carica i tag attualmente associati a quell'articolo
    //     $article = Article::with('tags')->findOrFail($id);

    //     $categories = Category::orderBy('name', 'asc')->get();
    //     $tags = Tag::orderBy('name', 'asc')->get();

    //     return view('articles.edit', [
    //         'article' => $article,
    //         'categories' => $categories,
    //         'tags' => $tags,
    //     ]);
    // }

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
    // public function update(UpdateArticleRequest $request, string $id)
    // {
    //     $validatedData = $request->validated();

    //     $article = Article::findOrFail($id);
    //     $article->update(collect($validatedData)->except('tags')->toArray());

    //     $article->tags()->sync($validatedData['tags'] ?? []);

    //     return redirect()->route('articles.index')->with('success', 'Articolo aggiornato con successo!');
    // }

    // Versione con route-model binding: Laravel risolve {article} in un oggetto
    // Article (404 automatico se non esiste), quindi niente findOrFail manuale.
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validatedData = $request->validated();

        // $article->update(collect($validatedData)->except('tags')->toArray());
        $dataToUpdate = $validatedData;
        unset($dataToUpdate['tags']); // Rimuove i tag in modo nativo

        $article->fill($dataToUpdate)
                ->save();

        $article->tags()->sync($validatedData['tags'] ?? []);

        return redirect()->route('dashboard')->with('success', 'Articolo aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     Article::destroy($id);
    //     return redirect()->route('articles.index')->with('success', 'Articolo eliminato con successo!');
    // }

    // Versione con route-model binding: ricevo già l'oggetto Article e lo elimino
    // con ->delete() (invece di Article::destroy($id) sulla stringa id).
    public function destroy(Article $article)
    {
        $article->delete($article);
        return redirect()->route('dashboard')->with('success', 'Articolo eliminato con successo!');
    }
}
