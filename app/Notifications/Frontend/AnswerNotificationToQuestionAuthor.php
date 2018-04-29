<?php

namespace App\Notifications\Frontend;

use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AnswerNotificationToQuestionAuthor extends Notification
{
    use Queueable;
    
    protected $question;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
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
            ->subject('Answer posted to your question: ' . $this->question->title)
            ->line('A new answer has been posted to your question: "' . $this->question->title . '". You are receiving this email because you have chosen to be notified in your User Settings.')
            ->action('View it here', url(route('frontend.user.questions.show', ['course' => $this->question->course, 'question' => $this->question->slug] )));
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
            'question_id' => $this->question->id,
            'question_title' => $this->question->title,
            'question_slug' => $this->question->slug,
            'course_slug' => $this->question->course->slug
        ];
    }
}
