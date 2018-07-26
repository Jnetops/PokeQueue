<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;

class AcceptedFriendRequest extends Notification
{
    use Queueable;
    protected $requeryFriend;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requeryFriend)
    {
        $this->requeryFriend = $requeryFriend;
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
        if (Auth::user()->trainer_name != $this->requeryFriend->received_uid)
        {
            $friendName = $this->requeryFriend->received_uid;
        }
        elseif (Auth::user()->trainer_name != $this->requeryFriend->request_uid)
        {
            $friendName = $this->requeryFriend->request_uid;
        }
        $friendArray = [
            'friend' => ['user' => $friendName, 'statement' => 'Has accepted your friend request']
        ];
        return $friendArray;
    }
}
