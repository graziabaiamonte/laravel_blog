<?php

// Nota il namespace: questo controller sta nella sottocartella Frontend,
// quindi il namespace deve includere \Frontend (deve rispecchiare il percorso del file).
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\IndexArticleRequest;
use App\Http\Resources\ArticleResource;

/**
 * Controller della parte PUBBLICA del blog.
 *
 * Si occupa SOLO di mostrare gli articoli a chiunque (anche ai non loggati):
 * - index: la lista di TUTTI gli articoli (homepage / pagina blog)
 * - show:  il singolo articolo per esteso
 *
 * Le operazioni di gestione (create/store/edit/update/destroy) NON stanno qui,
 * ma nel controller App\Http\Controllers\ArticleController.
 */
class ArticleController extends Controller
{
    /**
     * Mostra TUTTI gli articoli, con i filtri di ricerca/categoria/tag.
     * (È lo stesso index che avevi prima: l'ho semplicemente spostato qui.)
     */
    public function index(IndexArticleRequest $request)
    {
        $filters = $request->validated();

        $articles = Article::with('category', 'tags')
            ->search($filters['search'] ?? null)
            ->inCategory($filters['category_id'] ?? null)
            ->withTag($filters['tag_id'] ?? null)
            ->orderByDesc('created_at')
            ->get();

        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('articles.index', compact('articles', 'categories', 'tags'));
    }

    /**
     * Mostra il singolo articolo (route-model binding: Laravel risolve {article}
     * in un oggetto Article, con 404 automatico se non esiste).
     */
    public function show(Article $article)
    {
        $article->load('category', 'tags');
        return view('articles.show', ['article' => ArticleResource::make($article)]);
    }
}
