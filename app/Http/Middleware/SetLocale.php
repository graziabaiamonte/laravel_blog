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
        // 1) Priorità alla scelta MANUALE dell'utente (pulsante nell'header),
        //    salvata in sessione dalla rotta 'locale.switch'.
        $locale = $request->session()->get('locale');

        // 2) Se non c'è una scelta manuale valida, rilevo la lingua dal browser.
        //    getPreferredLanguage() legge l'header "Accept-Language" e sceglie,
        //    tra le lingue che gli passo, quella che l'utente preferisce.
        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);
        }

        // 3) Ultima rete di sicurezza: se ancora non è una lingua supportata,
        //    uso il fallback configurato in config/app.php.
        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
