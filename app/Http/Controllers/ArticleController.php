<?php
namespace App\Http\Controllers;
use App\Enums\Permission;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
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

        // Gli articoli dell'utente loggato
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
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete($article);
        return redirect()->route('admin.dashboard')->with('success', 'Articolo eliminato con successo!');
    }
}
