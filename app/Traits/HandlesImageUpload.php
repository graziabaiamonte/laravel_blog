<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Raccoglie in un unico posto la logica di upload/cancellazione delle immagini,
 * così non la ripetiamo in ogni controller (articoli, categorie, ...).
 *
 * Un "trait" è un blocco di metodi che puoi "incollare" dentro più classi con
 * la parola chiave `use`
 */
trait HandlesImageUpload
{
    /**
     * Salva un nuovo file immagine sul disco "public" dentro la cartella indicata
     * e ritorna il percorso salvato (es. "categories/abc123.jpg").
     *
     */
    protected function storeImage(?UploadedFile $file, string $folder): ?string
    {
        if (! $file instanceof UploadedFile || ! $file->isValid()) {
            return null;
        }

        // store() genera un nome univoco e ritorna il percorso relativo
        return $file->store($folder, 'public');
    }

    protected function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Decide quale percorso immagine salvare durante un aggiornamento
     */
    protected function resolveImageUpload($request, ?string $current, string $folder, string $field = 'image'): ?string
    {
        // nuovo file caricato -> sostituiamo
        if ($request->hasFile($field) && $request->file($field)->isValid()) {
            $this->deleteImage($current);

            return $request->file($field)->store($folder, 'public');
        }

        // l'utente ha chiesto di rimuovere l'immagine (checkbox "remove_image")
        if ($request->boolean('remove_' . $field)) {
            $this->deleteImage($current);

            return null;
        }

        // nessuna modifica 
        return $current;
    }
}
