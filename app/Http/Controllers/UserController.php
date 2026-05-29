<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * ⚠️ CONTROLLER DIDATTICO — NON è una best practice di sicurezza ⚠️
 *
 * Permette a QUALSIASI utente loggato di vedere, modificare ed eliminare
 * gli altri utenti. In un'applicazione reale questo va riservato agli
 * AMMINISTRATORI.
 *
 * Passo successivo (quando installeremo un pacchetto ruoli, es.
 * spatie/laravel-permission): proteggere queste rotte con un middleware tipo
 * 'role:admin' oppure una Policy, così solo gli admin potranno gestire gli utenti.
 */
class UserController extends Controller
{
    /**
     * Elenco di tutti gli utenti, così posso scegliere chi modificare/eliminare.
     */
    public function index(): View
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Form di modifica di uno specifico utente.
     * {user} viene risolto automaticamente da Laravel in un oggetto User (route-model binding).
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Salva le modifiche all'utente.
     * La validazione (e l'autorizzazione di base) avviene in UpdateUserRequest.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // fill() assegna in massa i campi validati al model...
        $user->fill($request->validated());

        // ...se l'email è cambiata, l'account non è più "verificato" per la nuova email.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utente aggiornato con successo!');
    }

    /**
     * Elimina un altro utente.
     *
     * Piccola sicurezza minima: impediamo all'utente di eliminare SE STESSO da qui
     * (per cancellare il proprio account c'è già ProfileController@destroy).
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if(
            $user->id === Auth::id(),
            403,
            'Per eliminare il tuo account usa la pagina del profilo.'
        );

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utente eliminato con successo!');
    }
}
