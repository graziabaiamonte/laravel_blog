<?php

namespace App\Enums;

enum CommentStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';

    /**
     * Etichetta leggibile per la UI (badge).
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'In attesa',
            self::Approved => 'Approvato',
        };
    }
}
