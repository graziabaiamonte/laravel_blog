<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

class ImageFile implements ValidationRule
{
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/avif',
    ];

    protected array $allowedExtensions = [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'gif',
        'avif',
    ];

    // 2 MB
    protected int $maxKilobytes = 2048;

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

        // getSize() torna i byte, quindi /1024 = kilobyte.
        if ($value->getSize() / 1024 > $this->maxKilobytes) {
            $fail("L'immagine non può superare i {$this->maxKilobytes} KB.");
            return;
        }

        // Controllo del MIME REALE: getMimeType() lo deduce dal contenuto del file,
        //    non dal nome
        if (! in_array($value->getMimeType(), $this->allowedMimeTypes, true)) {
            $fail('Il file deve essere un\'immagine (jpeg, png, webp, avif o gif).');
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
