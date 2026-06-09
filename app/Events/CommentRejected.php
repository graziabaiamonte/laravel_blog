<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Un commento sta per essere rifiutato/eliminato.
 */
class CommentRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Comment $comment
    ) {}
}
