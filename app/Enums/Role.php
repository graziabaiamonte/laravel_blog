<?php

namespace App\Enums;

/**
 * Enum dei RUOLI dell'applicazione
 */
enum Role: string
{
    case Admin = 'admin';
    case Author = 'author';

    /**
     * Etichetta leggibile per la UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Amministratore',
            self::Author => 'Autore',
        };
    }

    /**
     * Mappa ruolo -> permessi.
     *
     * @return Permission[]
     */
    public function permissions(): array
    {
        return match ($this) {
            self::Admin => [Permission::PublishArticles, Permission::ManageArticles],
            self::Author => [],
        };
    }
}
