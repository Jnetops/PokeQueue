<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\RaidTracker;
use App\Http\Models\Notifications;
use Carbon\Carbon;

class RemoveInactiveRaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveInactiveRaid:removeraid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Expired Raids';

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
      $raids = RaidTracker::where('raid_expire', '<=', Carbon::now())->get();
      $typeArray = array(
        'App\Notifications\RaidTrackerAdd',
      );

      $notifications = Notifications::whereIn('type', $typeArray)->get();
      foreach ($raids as $raid)
      {
        foreach ($notifications as $notification)
        {
          $data = $notification->data;
          if ($data['raid']['tracker_id'] == $raid->id)
          {
            Notifications::where('id', $notification->id)->delete();
          }
        }
        $raid->delete();
      }
    }
}
