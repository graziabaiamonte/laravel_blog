<?php

namespace App\Enums;

/**
 * Enum degli STATI di un articolo di tipo string
 */
enum ArticleStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    /**
     * Etichetta leggibile per la UI (badge, dropdown).
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Bozza',
            self::Published => 'Pubblicato',
        };
    }
}
