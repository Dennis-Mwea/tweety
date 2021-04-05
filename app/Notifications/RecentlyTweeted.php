<?php

namespace App\Notifications;

use App\Tweet;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecentlyTweeted extends Notification
{
    use Queueable;

    /**
     * @var Tweet
     */
    private $tweet;

    /**
     * Create a new notification instance.
     *
     * @param Tweet $tweet
     */
    public function __construct(Tweet $tweet)
    {
        $this->tweet = $tweet;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message(),
            'notifier' => $this->user(),
            'link' => $this->tweet->path(),
            'description' => $this->tweet->body,
            'screen' => '/tweet',
            'arg' => $this->tweet,
        ];
    }

    public function message()
    {
        return sprintf('%s recently tweeted', $this->user()->username);
    }

    /**
     * Get the associated user for the subject.
     */
    public function user()
    {
        return $this->tweet->user;
    }
}
