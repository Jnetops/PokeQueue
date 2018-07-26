<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use App\User;
use App\Http\Models\Group;
use App\Http\Models\Events;
use App\Http\Models\PokeTracker;
use App\Http\Models\RaidTracker;
use App\Http\Models\Notifications;
use App\Traits\Distance;
use Carbon\Carbon;

class HomeController extends Controller
{
    use Distance;
    protected $casts = [
        'users' => 'array',
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // make sure no private events are gathered
        // order by date, soonest upcoming to farthest out
        $userid = Auth::user()->id;
        $user = User::where('id', $userid)->first(['trainer_name', 'lat', 'lon']);
        $userLat = $user->lat;
        $userLon = $user->lon;
        $distanceArray = $this->gatherHighLow($userLat, $userLon, 25);

        // event array build
        $event = new Events();
        $eventArray = $event->Gather_Home_Events($distanceArray);

        // group array build
        $group = new Group();
        $groupArray = $group->Gather_Home_Groups($distanceArray);

        $pokemon = new PokeTracker();
        $pokeGather = $pokemon->Gather_Home_Poke($distanceArray);

        $raids = new RaidTracker();
        $raidGather = $raids->Gather_Home_Raids($distanceArray);

        return View::make('home', ['events' => $eventArray, 'groups' => $groupArray, 'pokemon' => $pokeGather, 'raids' => $raidGather]);
    }
}
