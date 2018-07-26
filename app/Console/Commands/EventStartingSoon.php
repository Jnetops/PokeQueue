<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Http\Models\Events;
use App\Http\Models\Notifications;
use App\Traits\SendNotifications;
use App\Traits\CacheReturn;
use Carbon\Carbon;
use Log;

class EventStartingSoon extends Command
{
    use SendNotifications;
    use CacheReturn;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EventStartingSoon:soon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify User Event Is Starting Soon';

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
        $events = $this->gather('events', 'return \App\Http\Models\Events::get();', 30);
        $eventsArray = [];
        foreach ($events as $event)
        {
          if ($event->datetime >= Carbon::now()->setTimezone($event->timezone)->addMinutes(30))
          {
            if ($event->datetime <= Carbon::now()->setTimezone($event->timezone)->addMinutes(32))
            {
              $eventsArray[] = $event;
            }
          }
        }

        $typeArray = array(
          'App\Notifications\EventStartingSoon',
        );

        $notifications = Notifications::whereIn('type', $typeArray)->get();
        $alreadyNotified = array();

        foreach ($notifications as $notification)
        {
          $data = $notification->data;
          $alreadyNotified[] = $data['event']['event_id'];
        }

        foreach ($eventsArray as $event)
        {
          $users = $event->users;
          $gatherUsers = User::whereIn('trainer_name', $users)->get();
          if (!in_array($event->id, $alreadyNotified))
          {
            $this->eventStartSoon($gatherUsers, $event);
          }
        }
    }
}
