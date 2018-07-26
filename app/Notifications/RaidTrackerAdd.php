<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;

class RaidTrackerAdd extends Notification
{
    use Queueable;
    protected $raidTrackerAdd;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($raidTrackerAdd)
    {
        $this->raidTrackerAdd = $raidTrackerAdd;
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
        $raidArray = [
          'raid' => ['user' => Auth::user()->trainer_name,
                        'statement' => 'Has added a ' . $this->raidTrackerAdd->star_level . ' Star ' . $this->raidTrackerAdd->pokemon_name . ' Raid to the RaidTracker',
                        'tracker_id' => $this->raidTrackerAdd->id
                      ]
        ];
        return $raidArray;
    }
}
