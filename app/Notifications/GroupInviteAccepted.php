<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;

class GroupInviteAccepted extends Notification
{
    use Queueable;

    protected $addToGroup;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($addToGroup)
    {
        $this->addToGroup = $addToGroup;
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
        $groupJoined = [
            'group' => [
                'user' => Auth::user()->trainer_name,
                'statement' => ' Has joined a group',
                'statement2' => ' Hosted by ',
                'host' => $this->addToGroup->admin,
                'group_id' => $this->addToGroup->id
            ]
        ];
        return $groupJoined;
    }
}
