<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Controller della parte PUBBLICA del blog.
 */
class ArticleController extends Controller
{
    public function index(IndexArticleRequest $request)
    {
        // ───────────────────────────────────────────────────────────────────────────
        // VERSIONE 1 — Eloquent "a mano" con i nostri scope (NON più usata)
        // ───────────────────────────────────────────────────────────────────────────
        // $filters = $request->validated();
        //
        // $articles = Article::with('category', 'tags')
        //     ->published()
        //     ->search($filters['search'] ?? null)
        //     ->inCategory($filters['category_id'] ?? null)
        //     ->withTag($filters['tag_id'] ?? null)
        //     ->orderByDesc('created_at')
        //     ->get();

        // ───────────────────────────────────────────────────────────────────────────
        // VERSIONE 2 — Spatie Query Builder
        // ───────────────────────────────────────────────────────────────────────────
        $filters = $request->validated();

        // Il form invia due ordinamenti separati (titolo e data). Li uniamo nella
        // stringa che Spatie si aspetta: ?sort=campo1,campo2
        $sort = implode(',', array_filter([
            $filters['sort_title'] ?? null,
            $filters['sort_date'] ?? null,
        ]));

        // Spatie, per convenzione JSON:API, legge i filtri dall'URL nel formato
        //    ?filter[nome]=valore. Il nostro form invia invece nomi "piatti"
        //    (search, category_id, tag_id)
        $queryRequest = new Request(array_filter([
            'filter' => array_filter([
                'search' => $filters['search'] ?? null,
                'category_id' => $filters['category_id'] ?? null,
                'tag_id' => $filters['tag_id'] ?? null,
            ]),
            'sort' => $sort ?: null,
        ]));

        // Il filtro per data lo applichiamo direttamente sulla query base con lo scope
        // createdBetween
        $base = Article::published()->createdBetween(
            $filters['date_from'] ?? null,
            $filters['date_to'] ?? null,
        );

        $articles = QueryBuilder::for($base, $queryRequest)
            ->allowedFilters(
                AllowedFilter::scope('search'),
                AllowedFilter::callback('category_id', fn ($q, $v) => $q->inCategories((array) $v)),
                AllowedFilter::callback('tag_id', fn ($q, $v) => $q->withTags((array) $v)),
            )
            ->allowedSorts('created_at', 'title')
            ->defaultSort('-created_at')
            ->with('category', 'tags')
            ->get();

        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('articles.index', compact('articles', 'categories', 'tags'));
    }

    public function show(Article $article)
    {
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
