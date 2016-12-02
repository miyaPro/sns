<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class BroadCastNotify extends Notification
{
    use Queueable;
    protected $broadCastInstance;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($broadCastInstance)
    {
        Log::info('construct broadcast');
        $this->broadCastInstance = $broadCastInstance;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        Log::info('via broadcast');
        return ['broadcast'];
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
        return [
            //
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
}
