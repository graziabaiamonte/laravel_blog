<?php

namespace App\Listeners;

use App\Events\ArticleDeleted;
use Illuminate\Support\Facades\Log;

class LogArticleDeleted
{
    /**
     * Handle the event.
     */
    public function handle(ArticleDeleted $event): void
    {
        Log::channel('article-deleted')
            ->info("L'articolo '{$event->title}' è stato eliminato da {$event->deletedBy}");
    }
}
