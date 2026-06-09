<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;

/**
 * Controller della parte PUBBLICA del blog.
 */
class ArticleController extends Controller
{
    public function index(IndexArticleRequest $request)
    {
        $filters = $request->validated();

        $articles = Article::with('category', 'tags')
            ->published()
            ->search($filters['search'] ?? null)
            ->inCategory($filters['category_id'] ?? null)
            ->withTag($filters['tag_id'] ?? null)
            ->orderByDesc('created_at')
            ->get();

        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('articles.index', compact('articles', 'categories', 'tags'));
    }

    public function show(Article $article)
    {
        // la bozza può essere vista solo dal suo autore e dall'admin
        if ($article->isDraft()) {
            $user = request()->user();

            $puoVederla = $user
                && ($user->id === $article->user_id
                    || $user->can(Permission::ManageArticles->value));

            abort_unless($puoVederla, 404);
        }

        // Carico anche i commenti col loro autore, dal più recente.
        $article->load([
            'category',
            'tags',
            'comments' => fn ($q) => $q->with('user')->latest(),
        ]);

        // Può moderare i commenti: il proprietario dell'articolo oppure l'admin.
        $user = request()->user();
        $canModerate = $user
            && ($user->id === $article->user_id
                || $user->can(Permission::ManageArticles->value));

        return view('articles.show', [
            'article' => ArticleResource::make($article),
            'canModerate' => $canModerate,
        ]);
    }
}
