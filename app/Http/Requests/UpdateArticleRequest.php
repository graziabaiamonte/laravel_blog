<?php

namespace App\Http\Requests;

use App\Rules\ImageFile;
use App\Rules\WithoutForbiddenWords;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'title'       => ['required', 'max:255', new WithoutForbiddenWords()],
            'content'     => ['required', new WithoutForbiddenWords()],
            'category_id' => 'nullable|exists:categories,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'image'        => ['nullable', 'file', new ImageFile()],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
