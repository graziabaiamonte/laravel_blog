<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class WithoutForbiddenWords implements ValidationRule
{
    protected array $forbiddenWords;

    public function __construct(?array $forbiddenWords = null)
    {
        $this->forbiddenWords = $forbiddenWords ?? config('validation.forbidden_words', []);
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        foreach ($this->forbiddenWords as $word) {
            if (stripos($value, $word) !== false) {
                $fail("The :attribute contains forbidden words.");
                return;
            }
        }
    }
}

// stripos  >  confronto case-insensitive
