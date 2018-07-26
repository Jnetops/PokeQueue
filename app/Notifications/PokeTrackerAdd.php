<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;

class PokeTrackerAdd extends Notification
{
    use Queueable;
    protected $pokeTrackerAdd;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pokeTrackerAdd)
    {
        $this->pokeTrackerAdd = $pokeTrackerAdd;
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
        $pokeArray = [
          'pokemon' => ['user' => Auth::user()->trainer_name,
                        'pokemon' => $this->pokeTrackerAdd->pokemon_name,
                        'statement' => 'Has added ',
                        'statement2' => 'To the PokeTracker',
                        'tracker_id' => $this->pokeTrackerAdd->id
                      ]
        ];
        return $pokeArray;
    }
}
