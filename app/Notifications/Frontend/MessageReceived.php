<?php

namespace App\Notifications\Frontend;

use App\Models\Auth\User;
use Illuminate\Bus\Queueable;
use Cmgmyr\Messenger\Models\Message;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageReceived extends Notification
{
    use Queueable;

    public $sender;
    public $recipient;
    public $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $recipient, User $sender, Message $message)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject(config('app.name') . ": New message from ". $this->sender->name)
                ->line('You have a new message from ' . $this->sender->name . '.')
                ->action('Go to your inbox', url('/inbox'));
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
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'message' => str_limit($this->message->body, 50)
        ];
    }
}
