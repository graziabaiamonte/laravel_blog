<?php

namespace App\Listeners;

use App\Events\CommentRejected;
use App\Notifications\CommentRejectedNotification;

/**
 * Quando un commento viene rifiutato/eliminato, avvisa chi l'ha scritto.
 */
class SendCommentRejectedNotification
{
    public function handle(CommentRejected $event): void
    {
        $comment = $event->comment;

        $comment->user->notify(
            new CommentRejectedNotification($comment->article->title)
        );
    }
}
