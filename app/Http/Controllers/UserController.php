<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

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
        // Passo al form l'elenco dei ruoli, per popolare il menu a tendina.
        $roles = Role::orderBy('name', 'asc')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        // È l'admin che sta modificando SE STESSO?
        $isSelf = $user->id === Auth::id();

        if ($isSelf && array_key_exists('role', $validated)) {
            abort(403, 'Non puoi modificare il tuo stesso ruolo.');
        }

        // fill() aggiorna nome ed email. Escludo 'role' perché NON è una colonna
        // della tabella users: il ruolo si gestisce a parte con syncRoles().
        $user->fill(collect($validated)->except('role')->toArray());
        $user->save();

        // Applico il nuovo ruolo solo se sto modificando un ALTRO utente.
        // syncRoles() SOSTITUISCE i ruoli con quello scelto (uno solo alla volta).
        if (! $isSelf && ! empty($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

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
