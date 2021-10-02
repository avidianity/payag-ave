<?php

namespace App\Notifications;

use App\Channels\SemaphoreChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisteredSMS extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'semaphore'];
    }

    public function toSemaphore($notifiable)
    {
        return [
            'message' => $this->message,
            'user' => $notifiable,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'user' => $notifiable,
        ];
    }
}
