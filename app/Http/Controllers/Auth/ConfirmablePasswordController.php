<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {

        //  un guard è una "strategia di autenticazione". web è il guard standard per le sessioni del browser
        if (! Auth::guard('web')->validate([

            // prendiamo l'email dell'utente loggato.
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        // redireziona l'utente alla pagina che stava cercando di visitare prima di essere intercettato dal form di conferma password. Se non c'è una pagina "intended" memorizzata, ricade sull'URL passato come fallback (qui dashboard).
        return redirect()->intended(route('admin.dashboard', absolute: false));
    }
}
