<?php

namespace App\Enums;

/**
 * Enum dei PERMESSI dell'applicazione di tipo string
 */
enum Permission: string
{
    case PublishArticles = 'publish articles';
    case ManageArticles = 'manage articles';

    /**
     * Etichetta leggibile per la UI
     */
    public function label(): string
    {
        return match ($this) {
            self::PublishArticles => 'Pubblicare i propri articoli',
            self::ManageArticles => 'Gestire gli articoli di tutti',
        };
    }
}
