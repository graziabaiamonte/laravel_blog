<?php

namespace App\Listeners;

use App\Events\CommentApproved;
use App\Notifications\CommentApprovedNotification;

/**
 * Quando un commento viene approvato, avvisa chi l'ha scritto.
 */
class SendCommentApprovedNotification
{
    public function handle(CommentApproved $event): void
    {
        // notifiable = l'autore del commento.
        $event->comment->user->notify(
            new CommentApprovedNotification($event->comment)
        );
    }
}
