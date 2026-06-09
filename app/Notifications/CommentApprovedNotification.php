<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Mail a CHI HA SCRITTO il commento: è stato approvato e pubblicato.
 */
class CommentApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $article = $this->comment->article;

        return (new MailMessage)
            ->subject('Il tuo commento è stato pubblicato')
            ->greeting('Ciao '.$notifiable->name.'!')
            ->line('Il tuo commento sull’articolo «'.$article->title.'» è stato approvato ed è ora visibile pubblicamente.')
            ->action('Vedi il commento', route('articles.show', $article->id).'#commenti');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'article_id' => $this->comment->article_id,
        ];
    }
}
