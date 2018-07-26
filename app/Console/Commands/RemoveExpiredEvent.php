<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveExpiredEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveExpiredEvent:removeevent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Expired Event Notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $typeArray = array(
        'App\Notifications\EventInviteAccepted',
        'App\Notifications\EventInviteRequest',
        'App\Notifications\EventInviteSend',
        'App\Notifications\CreateEvent',
        'App\Notifications\EventStartingSoon',
      );

      $events = Events::where('datetime', '<=', Carbon::now())->get();
      $notifications = Notifications::whereIn('type', $typeArray)->get();
      foreach ($events as $event)
      {
        foreach ($notifications as $notification)
        {
          $data = $notification->data;
          if ($data['event']['event_id'] == $event->id)
          {
            Notifications::where('id', $notification->id)->delete();
          }
        }
      }
    }
}
