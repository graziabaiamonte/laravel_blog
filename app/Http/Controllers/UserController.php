<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 *
 * Permette all'utente loggato di vedere, modificare ed eliminare
 * gli altri utenti. 
 */
class UserController extends Controller
{
    /**
     * Elenco di tutti gli utenti
     */
    public function index(): View
    {
        $users = User::orderBy('name', 'asc')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Form di modifica di uno specifico utente.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // fill() assegna in massa i campi validati al model
        $user->fill($request->validated());

        // ...se l'email è cambiata, l'account non è più "verificato" per la nuova email.
        // if ($user->isDirty('email')) {
        //     $user->email_verified_at = null;
        // }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utente aggiornato con successo!');
    }

    /**
     * Elimina un altro utente.
     *
     * (per cancellare il proprio account c'è già ProfileController@destroy).
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if(
            $user->id === Auth::id(),
            403,
            'Per eliminare il tuo account usa la pagina del profilo.'
        );

        $user->delete($user);

        return redirect()->route('admin.users.index')->with('success', 'Utente eliminato con successo!');
    }
}
