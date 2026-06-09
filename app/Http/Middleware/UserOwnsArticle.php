<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOwnsArticle
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->can(Permission::ManageArticles->value)) {
            return $next($request);
        }

        // Recupero il parametro {article} dalla rotta
        $article = $request->route('article');

        abort_if(
            (int) $article->user_id !== (int) $request->user()->id,
            403,
            'Non sei autorizzato a gestire questo articolo.'
        );

        return $next($request);
    }
}
