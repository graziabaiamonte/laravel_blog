<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Http\Requests\Auth\RegisterRequest;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // crea un nuovo record nella tabella users
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $user->assignRole(Role::Author->value);

        // emette un evento chiamato Registered. Altre parti dell'app possono essere "in ascolto" di questo evento e reagire. Per default Laravel ha un listener che, se l'utente deve verificare l'email, gli invia automaticamente l'email di verifica. È un modo per disaccoppiare il codice: il controller non deve sapere cosa succede dopo.
        event(new Registered($user));

        // autentica l'utente appena registrato
        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }
}
