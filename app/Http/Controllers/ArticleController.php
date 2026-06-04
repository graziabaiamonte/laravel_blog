<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Traits\HandlesImageUpload;

/**
 * GESTIONE degli articoli per gli utenti loggati
 *
 */
class ArticleController extends Controller
{

    use HandlesImageUpload;

    public function index()
    {
        $user = request()->user();

        // Gli articoli dell'utente loggato
        $articles = Article::with('category', 'tags')
            ->ownedBy($user)
            ->orderByDesc('created_at')
            ->get();

        $othersArticles = null;
        if ($user->can('manage articles')) {
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

        $validatedData['image'] = $this->storeImage($request->file('image'), 'articles');

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

        // resolveImageUpload gestisce i tre casi (nuovo file / rimozione / invariato),
        // cancellando da solo il vecchio file quando serve.
        $dataToUpdate['image'] = $this->resolveImageUpload($request, $article->image, 'articles');

        $article->fill($dataToUpdate)->save();
        $article->tags()->sync($validatedData['tags'] ?? []);

        return redirect()->route('admin.dashboard')->with('success', 'Articolo aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Prima il file dal disco, poi il record dal DB.
        $this->deleteImage($article->image);

        $article->delete($article);
        return redirect()->route('admin.dashboard')->with('success', 'Articolo eliminato con successo!');
    }
}
