<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ArticleDeleted
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @param  string  $title  il titolo dell'articolo eliminato
     * @param  string  $deletedBy  il nome dell'utente che ha eseguito l'eliminazione
     */
    public function __construct(
        public string $title,
        public string $deletedBy,
    ) {}
}
