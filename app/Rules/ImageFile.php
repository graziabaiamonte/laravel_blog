<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

class ImageFile implements ValidationRule
{
    protected array $allowedMimeTypes;
    protected array $allowedExtensions;
    protected int $maxKilobytes;

    public function __construct()
    {
        $this->allowedMimeTypes  = config('media.images.mime_types');
        $this->allowedExtensions = config('media.images.extensions');
        $this->maxKilobytes      = config('media.images.max_size');
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // phpinfo();

        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('Il file caricato non è valido.');
            return;
        }

        if ($value->getSize() / 1024 > $this->maxKilobytes) {
            $fail("L'immagine non può superare i {$this->maxKilobytes} KB.");
            return;
        }

        if (! in_array($value->getMimeType(), $this->allowedMimeTypes, true)) {
            $fail('Il file deve essere un\'immagine: ' . implode(', ', $this->allowedExtensions) . '.');
            return;
        }

        // Controllo aggiuntivo sull'ESTENSIONE come doppia rete di sicurezza.
        $extension = strtolower($value->getClientOriginalExtension());
        if (! in_array($extension, $this->allowedExtensions, true)) {
            $fail('Estensione non ammessa. Sono consentite solo immagini: ' . implode(', ', $this->allowedExtensions) . '.');
            return;
        }
    }
}
