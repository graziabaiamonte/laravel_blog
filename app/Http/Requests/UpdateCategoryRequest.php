<?php

namespace App\Http\Requests;

use App\Rules\ImageFile;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'image'        => ['nullable', 'file', new ImageFile()],
            // flag della checkbox "Rimuovi l'immagine attuale" nel form di modifica
            'remove_image' => 'nullable|boolean',
        ];
    }
}
