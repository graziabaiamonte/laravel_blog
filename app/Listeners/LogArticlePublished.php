<?php

namespace App\Listeners;

use App\Events\ArticlePublished;
use Illuminate\Support\Facades\Log;

class LogArticlePublished
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
    public function handle(ArticlePublished $event): void
    {
        // Scriviamo sul canale dedicato (storage/logs/ArticlePublishedLog.log),
        // separato dal laravel.log generale.
        Log::channel('article-published')
            ->info("L'articolo '{$event->article->title}' è stato pubblicato");
    }
}
