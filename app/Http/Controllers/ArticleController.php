<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Support\Facades\Storage;

/**
 * GESTIONE degli articoli per gli utenti loggati
 *
 */
class ArticleController extends Controller
{
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
        //     'tags.*' => 'exists:tags,id',
        // ]);

        $validatedData = $request->validated();

        // Se è stato caricato un file immagine, lo salviamo su disco "public"
        // dentro la cartella "articles". store() genera un nome univoco e
        // restituisce il percorso che salviamo in DB.
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('articles', 'public');
        }

        // $request->user() restituisce l'utente autenticato
        $article = $request->user()->articles()->create(
            collect($validatedData)->except(['tags', 'remove_image'])->toArray()
        );

        $article->tags()->sync($validatedData['tags'] ?? []);

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
       
        // Togliamo le chiavi che NON sono colonne dirette da aggiornare con fill():
        // 'tags' (relazione pivot) e 'remove_image' (flag della checkbox).
        unset($dataToUpdate['tags'], $dataToUpdate['remove_image']);

        if ($request->hasFile('image')) {

            // Prima cancelliamo il vecchio (se esisteva), poi salviamo il nuovo.
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $dataToUpdate['image'] = $request->file('image')->store('articles', 'public');
        } elseif ($request->boolean('remove_image')) {
            // l'utente ha spuntato "rimuovi immagine" senza caricarne una nuova.
            
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $dataToUpdate['image'] = null;
        }
       
        // nessun file e nessuna richiesta di rimozione → l'immagine resta invariata.
        $article->fill($dataToUpdate)->save();
        $article->tags()->sync($validatedData['tags'] ?? []);
        
        return redirect()->route('admin.dashboard')->with('success', 'Articolo aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete($article);
        return redirect()->route('admin.dashboard')->with('success', 'Articolo eliminato con successo!');
    }
}
