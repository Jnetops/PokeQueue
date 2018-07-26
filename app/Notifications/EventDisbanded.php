<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;

class EventDisbanded extends Notification
{
    use Queueable;
    protected $eventsCheck;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($eventsCheck)
    {
        $this->eventsCheck = $eventsCheck;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->action('Notification Action', url('/'))
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
        $eventDisband = ['event' => ['user' => $this->eventsCheck->admin,
                         'statement' => ' Has disbanded an event scheduled for ',
                         'date' => date('F d, Y', strtotime($this->eventsCheck->datetime)),
                         'event_id' => $this->event->id
                       ]
        ];
        return $eventDisband;
    }
}
