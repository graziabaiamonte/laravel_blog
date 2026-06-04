<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOwnsArticle
{
    /**
     * Handle an incoming request.
     *
     * Lascia proseguire la richiesta se l'utente loggato ha il permesso
     * 'manage articles' (es. admin) OPPURE è il proprietario dell'articolo
     * richiesto nell'URL
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->can('manage articles')) {
            return $next($request);
        }

        // Recupero il parametro {article} dalla rotta
        $article = $request->route('article');

        // Confronto il proprietario dell'articolo con l'utente loggato.
        // Se NON coincidono, blocco la richiesta con un 403
        abort_if(
            (int) $article->user_id !== (int) $request->user()->id,
            403,
            'Non sei autorizzato a gestire questo articolo.'
        );
        
        return $next($request);
    }
}
