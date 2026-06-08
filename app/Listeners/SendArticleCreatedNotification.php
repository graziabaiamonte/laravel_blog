<?php

namespace App\Listeners;

use App\Events\ArticleCreated;
use App\Notifications\ArticleCreated as ArticleCreatedNotification;

class SendArticleCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ArticleCreated $event): void
    {
        $event->article->user->notify(
            new ArticleCreatedNotification($event->article)
        );
    }
}
