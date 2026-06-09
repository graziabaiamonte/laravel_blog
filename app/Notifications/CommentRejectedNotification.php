<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Mail a CHI HA SCRITTO il commento: non è stato accettato.
 */
class CommentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $articleTitle) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Il tuo commento non è stato accettato')
            ->greeting('Ciao '.$notifiable->name.'!')
            ->line('Il tuo commento sull’articolo «'.$this->articleTitle.'» non è stato accettato dal proprietario dell’articolo ed è stato rimosso.')
            ->line('Puoi sempre scriverne uno nuovo nel rispetto delle regole della community.');
    }
}
