<?php

namespace App\Listeners;

use App\Events\CommentSubmitted;
use App\Notifications\NewCommentNotification;

/**
 * Quando arriva un nuovo commento, avvisa il proprietario dell'articolo.
 */
class SendCommentSubmittedNotification
{
    public function handle(CommentSubmitted $event): void
    {
        // notifiable = l'autore dell'articolo commentato.
        $event->comment->article->user->notify(
            new NewCommentNotification($event->comment)
        );
    }
}
