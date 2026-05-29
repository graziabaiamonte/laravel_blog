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
     * Lascia proseguire la richiesta SOLO se l'utente loggato è il proprietario
     * dell'articolo richiesto nell'URL. Altrimenti blocca con un errore 403.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recupero il parametro {article} dalla rotta
        $article = $request->route('article');

        // Confronto il proprietario dell'articolo con l'utente loggato.
        // Se NON coincidono, blocco la richiesta con un 403
        abort_if(
            (int) $article->user_id !== (int) $request->user()->id,
            403,
            'Non sei autorizzato a gestire questo articolo.'
        );

        // Tutto ok: la richiesta prosegue verso il controller.
        return $next($request);
    }
}
