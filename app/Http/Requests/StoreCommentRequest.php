<?php

namespace App\Http\Requests;

use App\Rules\WithoutForbiddenWords;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000', new WithoutForbiddenWords],
        ];
    }

    /**
     * Messaggi personalizzati
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Il commento non può essere vuoto.',
            'body.max' => 'Il commento è troppo lungo (massimo 2000 caratteri).',
        ];
    }
}
