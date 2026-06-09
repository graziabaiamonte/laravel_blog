<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOwnsComment
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->can(Permission::ManageArticles->value)) {
            return $next($request);
        }

        // Recupero il parametro {comment} dalla rotta e risalgo al suo articolo.
        $comment = $request->route('comment');

        abort_if(
            (int) $comment->article->user_id !== (int) $request->user()->id,
            403,
            'Non sei autorizzato a moderare questo commento.'
        );

        return $next($request);
    }
}
