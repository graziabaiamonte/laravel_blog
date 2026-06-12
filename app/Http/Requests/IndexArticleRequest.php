<?php

namespace App\Http\Requests;

// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexArticleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:100',

            // Filtri multipli: arrivano come array (checkbox name="category_id[]").
            // La regola '.*' valida ogni singolo elemento dell'array.
            'category_id' => 'nullable|array',
            'category_id.*' => 'integer|exists:categories,id',
            'tag_id' => 'nullable|array',
            'tag_id.*' => 'integer|exists:tags,id',

            // Filtro per data (input type="date" → formato Y-m-d).
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',

            // I valori devono coincidere con allowedSorts().
            'sort_title' => 'nullable|in:title,-title',
            'sort_date' => 'nullable|in:created_at,-created_at',
        ];
    }
}
