<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Un commento è stato approvato dal proprietario dell'articolo.
 */
class CommentApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Comment $comment
    ) {}
}
