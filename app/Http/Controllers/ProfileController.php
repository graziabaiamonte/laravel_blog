<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);

        // Il secondo parametro di view() è un array di dati che vengono "passati" alla view. Qui passiamo l'utente loggato ($request->user()) con la chiave user. Dentro Blade potrai usare la variabile $user per accedere ai suoi dati (es. {{ $user->email }}
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        // fill() è un metodo di Eloquent che assegna in massa quei dati alle proprietà del modello
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            // infatti se l'utente cambia indirizzo email, la nuova email non è ancora verificata
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // userDeletion è il nome di un error bag: contenitore separato per gli errori, in modo che se fossero presenti più form, il messaggio di errore venga mostrato solo nel form corretto

        $user = $request->user();

        Auth::logout();

        $user->delete($request);

        // distrugge tutti i dati della sessione
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
