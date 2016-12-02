<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use App\Repositories\NotificationRepository;

class WorkoutAssigned extends Notification
{
    use Queueable;

    private $workout;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($workout)
    {
        $this->workout = $workout;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return ['mail', 'database', 'nexmo' , 'broadcast', 'slack'];
        return ['mail', 'database'];
//        return $notifiable->prefers_sms ? ['mail'] : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Log::info('1 toMail');
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        Log::info('toArray or database');
        return [
            'data' => 'A new post was published on Laravel News.',
//            'type' => 'type',
//            'notifiable_id' => 'notifiable_id',
//            'notifiable_type' => 'notifiable_type',
//            'read_at' => 'read_at'
        ];
    }

    public function toBroadcast($notifiable){
        Log::info('toBroadcast');
        return [
            'data' => 'test',
            'read_at' => 'test',
            'notifiable_id' => 'test',
            'notifiable_type' => 'test',
            'type' => 'test'
        ];
    }

    public function toDatabase($notifiable)
    {
        Log::info('toDatabase');
        return  new DatabaseMessage([
            'data' => 'A new post was published on Laravel News.',
            'type' => 'type',
            'notifiable_id' => 'notifiable_id',
            'notifiable_type' => 'notifiable_type',
            'read_at' => 'read_at'
        ]);
    }

    public function queue($notifiable)
    {
        return [
            'mail' => [
                'connection' => 'sqs',
                'queue' => 'high',
                'delay' => 60,
            ],
            'database' => [
                // don't set some values to use the defaults
                'queue' => 'low',
            ],
        ];
    }
}
