<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\Http\Models\Group;
use App\User;
use App\Traits\SendNotifications;
use App\Events\disbandAll;
use App\Traits\CacheReturn;

class DisbandInactiveGroup extends Command
{
    use SendNotifications;
    use CacheReturn;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DisbandInactiveGroup:disbandgroup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disband Inactive Groups';

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
        $groups = Group::where('created_at', '<=', Carbon::now()->subHours(12))->get();
        foreach ($groups as $group)
        {
          $users = $group->users;
          $userResults = User::whereIn('trainer_name', $users)->get();
          $this->AutoGroupDisbanded($userResults, $group);

          $public = array('type' => 'public', 'id' => $group->id, 'subType' => 'group');
          $publicEvent = event(new disbandAll($public));
          $private = array('type' => 'private', 'id' => $group->id, 'subType' => 'group');
          $privateEvent = event(new disbandAll($private));

          Group::where('id', $group->id)->delete();
          Group_Invite::where('group-id', $group->id)->delete();
          Group_Chat::where('group-id', $group->id)->delete();
        }
        $this->updateCache('groups');
    }
}
