<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;

class GroupDisbanded extends Notification
{
    use Queueable;
    protected $checkGroup;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($checkGroup, $type)
    {
        $this->checkGroup = $checkGroup;
        $this->type = $type;
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
        if ($this->type == 'User')
        {
          $groupDisband = ['group' => ['user' => $this->checkGroup->admin,
                           'statement' => ' Has disbanded your group']
          ];
        }
        else {
          $groupDisband = ['group' => ['user' => 'Administrator',
                           'statement' => ' Has Automatically Disbanded Your Group: Time Limit']
          ];
        }
        return $groupDisband;
    }
}
