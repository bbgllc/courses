<?php

namespace App\Notifications\Frontend;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawalRequestReceived extends Notification
{
    use Queueable;
    
    protected $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
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
                ->from('no-reply@devprojects.io', config('app.name'))
                ->subject('['.config('app.name').'] Your request for withdrawal was received')
                ->line('We have received your request to withdraw $' . $this->withdrawal->amount . '.')
                ->line('Your funds will be deposited in the PayPal account ' . $this->withdrawal->paypal_email . '.')
                ->action('You can manage your requests here', url('/revenue'));
                
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
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'status' => $this->withdrawal->status,
            'comment' => $this->withdrawal->comment
        ];
    }
}
