<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Un nuovo commento è stato inviato ed è in attesa di moderazione.
 */
class CommentSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Comment $comment
    ) {}
}
