<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Le lingue che l'applicazione supporta.
     * Se il browser chiede una lingua non presente qui, si usa il fallback.
     */
    private const SUPPORTED_LOCALES = ['it', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // getPreferredLanguage() legge l'header "Accept-Language" del browser
        // e sceglie, tra le lingue che gli passo, quella che l'utente preferisce.
        // Se nessuna combacia, ritorna il primo valore della lista (qui 'it'),
        // quindi forzo subito dopo il fallback su config('app.fallback_locale').
        $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
