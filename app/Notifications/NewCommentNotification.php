<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * Mail al PROPRIETARIO dell'articolo: c'è un nuovo commento da moderare.
 * implements ShouldQueue => l'invio finisce in coda (tabella jobs) e parte
 * fuori dalla richiesta, gestito da `php artisan queue:work`.
 */
class NewCommentNotification extends Notification implements ShouldQueue
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
            ->subject('Nuovo commento da moderare: '.$article->title)
            ->greeting('Ciao '.$notifiable->name.'!')
            ->line($this->comment->user->name.' ha commentato il tuo articolo «'.$article->title.'».')
            ->line('«'.Str::limit($this->comment->body, 150).'»')
            ->action('Modera il commento', route('articles.show', $article->id).'#commenti')
            ->line('Dalla pagina dell’articolo puoi approvarlo o eliminarlo.');
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
