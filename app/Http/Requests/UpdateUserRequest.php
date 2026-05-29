<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validazione per la modifica di un utente QUALSIASI da parte di un altro utente.
 *
 * È molto simile a ProfileUpdateRequest, con UNA differenza importante:
 * - in ProfileUpdateRequest l'utente modifica SE STESSO  -> ignore($this->user()->id)
 * - qui modifichiamo un ALTRO utente, preso dalla rotta -> ignore($this->route('user')->id)
 *
 * "ignore(...)" serve sulla regola unique dell'email: dice a Laravel di NON
 * considerare un errore il fatto che l'email appartenga già all'utente che
 * stiamo modificando (altrimenti non potresti salvare lasciando la stessa email).
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
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
        ];
    }
}
