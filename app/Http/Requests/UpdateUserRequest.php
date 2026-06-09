<?php

namespace App\Http\Requests;

use App\Models\User;
// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validazione per la modifica di un utente qualsiasi da parte di un altro utente.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isSelf = $this->route('user')->id === $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // $this->route('user') è l'utente {user} preso dall'URL (route-model binding).
                Rule::unique(User::class)->ignore($this->route('user')->id),
            ],
            // 'role' deve essere il nome di un ruolo esistente nella tabella roles.
            'role' => [$isSelf ? 'nullable' : 'required', Rule::exists('roles', 'name')],
        ];
    }
}
