<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Traits\Distance;
use App\User;
use Carbon\Carbon;
use File;
use App\Traits\SendNotifications;
use App\Traits\GoogleApi;

class RaidTracker extends Model
{
    use SendNotifications;
    use GoogleApi;
    use Distance;
    public $timestamps = false;
    protected $fillable = ['gym_lat', 'gym_lon', 'pokemon_name', 'pokemon_id', 'address', 'location', 'raid_expire', '', 'star_level'];
    protected $table = 'raidtracker';
    public function Add_Raid($request)
    {
        if ($request->has('gym_lat') && $request->has('gym_lon'))
        {
            $raidCheck = RaidTracker::where('star_level', $request->input('star_level'))
                        ->where('gym_lat', $request->input('gym_lat'))
                        ->where('gym_lon', $request->input('gym_lon'))
                        ->where('raid_expire', '>=', Carbon::now())
                        ->first();

            $location = $request->input('gym_lat') . ', ' . $request->input('gym_lon');

            $locationCheck = $this->GoogleLocation($location, 'latlng');
            if ($locationCheck['Success'] == 'False')
            {
              return array('Success' => 'False', 'Error' => 'Unable to gather location');
            }
            else {
              foreach ($locationCheck['results'] as $one)
              {
                foreach ($one['address_components'] as $two)
                {
                  if (array_key_exists('types', $two))
                  {
                    if (in_array('locality', $two['types']))
                    {
                      $city = $two['long_name'];
                    }
                    elseif (in_array('administrative_area_level_1', $two['types']))
                    {
                      $state = $two['short_name'];
                    }
                  }
                }
              }
              $location = $city . ', ' . $state;
              $address = $locationCheck['results'][0]['address_components'][0]['long_name'] . ' ' . $locationCheck['results'][0]['address_components'][1]['long_name'];
              $request->request->add(['location' => $location, 'address' => $address]);
            }
        }
        elseif ($request->has('address') && $request->has('gym_city') && $request->has('gym_state'))
        {
            $address = $request->input('address') . ', ' . ucfirst($request->input('gym_city')) . ', ' . $request->input('gym_state');
            $location = ucfirst($request->input('gym_city')) . ', ' . $request->input('gym_state');

            $locationCheck = $this->GoogleLocation($address, 'address');
            if ($locationCheck['Success'] == 'False')
            {
              return array('Success' => 'False', 'Error' => 'Unable to gather location');
            }
            else {
              $request->request->add(['location' => $location, 'gym_lat' => $locationCheck['lat'], 'gym_lon' => $locationCheck['lon']]);
            }
            // get googles results for the address provided, if user types address in slightly differently itll still be the same lat/lon
            // so wait for google to return lat/lon and query db by that, more accurate
            $raidCheck = RaidTracker::where('star_level', $request->input('star_level'))
                        ->where('gym_lat', $locationCheck['lat'])
                        ->where('gym_lon', $locationCheck['lon'])
                        ->where('raid_expire', '>=', Carbon::now())
                        ->first();

        }
        else {
            return array('Success' => 'False', 'Error' => 'Please Supply Lat / Long or Address');
        }

        if ($raidCheck)
        {
            return array('Success' => 'False', 'Error' => 'Raid Already Exists');
        }
        else {
            $expireTime = Carbon::now()->addHours($request->input('raid_expire_hour'))->addMinutes($request->input('raid_expire_minute'));
            $request->request->add(['raid_expire' => $expireTime]);

            $path = public_path() . '/protos/rarity.json';
            if (!File::exists($path)) {
                return array('Success' => 'False', 'Error' => 'Unable to gather pokemon information');
            }

            $file = File::get($path); // string
            $file = json_decode($file, true);

            foreach ($file as $key => $pokemon)
            {
              if ($key == $request->input('pokemon_id'))
              {
                $pokemonName = $pokemon['name'];
              }
            }

            $request->request->add(['pokemon_name' => $pokemonName]);

            // create database entry, no users have submitted anything for this yet
            $raidCreate = RaidTracker::create($request->except(['city', 'state']));
            if ($raidCreate)
            {
              $regetRaid = RaidTracker::where('id', $raidCreate->id)->first();
              $friends = Friends::where([['request_uid', Auth::user()->trainer_name], ['status', 'accepted']])->orWhere([['received_uid', Auth::user()->trainer_name], ['status', 'accepted']])->get();

              $friendArray = [];
              foreach ($friends as $friend)
              {
                if (ucfirst($friend->received_uid) != ucfirst(Auth::user()->trainer_name))
                {
                  $friendArray[] = ucfirst($friend->received_uid);
                }
                elseif (ucfirst($friend->request_uid) != ucfirst(Auth::user()->trainer_name)) {
                  $friendArray[] = ucfirst($friend->request_uid);
                }
              }

              $users = User::whereIn('trainer_name', $friendArray)->get();

              $this->raidTrackerAdd($users, $regetRaid);

              return array('Success' => 'True', 'raids' => $regetRaid);
            }
            else {
              return array('Success' => 'False', 'Error' => 'Failed to create raid');
            }
        }
    }

    public function Get_Raids($request)
    {
      if ($request->has('distance'))
      {
        $distance = $request->input('distance');
      }
      else {
        if ($request->has('filter-id'))
        {
          $gatherRaids = RaidTracker::where('id', $request->input('filter-id'))
              ->where('raid_expire', '>=', Carbon::now())
              ->orderBy('raid_expire', 'asc')
              ->simplePaginate(1);

          if ($gatherRaids->count())
          {
            return array('Success' => 'True', 'raids' => $gatherRaids, 'Filter' => 'True');
          }
          else {
            $maxID = RaidTracker::find(\DB::table('raidtracker')->max('id'));
            if ($maxID)
            {
              if ($maxID->id > $request->input('filter-id'))
              {
                return array('Success' => 'False', 'Expired' => 'True');
              }
            }
            return array('Success' => 'False', 'Found' => 'False');
          }
        }
        return array('Success' => 'False', 'Error' => 'Distance must be supplied');
      }
      $userid = Auth::user()->id;
      $lat = Auth::user()->lat;
      $lon = Auth::user()->lon;
  		$distanceArray = $this->gatherHighLow($lat, $lon, $distance);
  		if (array_key_exists('error', $distanceArray))
  		{
  		  return array('Success' => 'False', 'Error' => $distanceArray['error']);
  		}
  		else {
            if ($request->has('pokemon-id'))
            {
              $gatherRaids = RaidTracker::where('pokemon_id', $request->input('pokemon-id'))
  								->where('raid_expire', '>=', Carbon::now())
  								->whereBetween('gym_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
  								->whereBetween('gym_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
  								->orderBy('raid_expire', 'asc')
  								->simplePaginate(16);

              return array('Success' => 'True', 'raids' => $gatherRaids);
            }
            elseif ($request->has('raid-tier'))
            {
              $gatherRaids = RaidTracker::where('star_level', $request->input('raid-tier'))
                  ->where('raid_expire', '>=', Carbon::now())
                  ->whereBetween('gym_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                  ->whereBetween('gym_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                  ->orderBy('raid_expire', 'asc')
                  ->simplePaginate(16);

              return array('Success' => 'True', 'raids' => $gatherRaids);
            }
            else {
                $gatherRaids = RaidTracker::where('raid_expire', '>=', Carbon::now())
  								->whereBetween('gym_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
  								->whereBetween('gym_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
  								->orderBy('raid_expire', 'asc')
  								->simplePaginate(16);
                return array('Success' => 'True', 'raids' => $gatherRaids);
            }
  		  // modify get() to only get array of colunms relavent to user
  		}
    }

    public function Gather_Home_Raids($distanceArray)
    {
      $gatherRaids = RaidTracker::where('raid_expire', '>=', Carbon::now())
        ->whereBetween('gym_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
        ->whereBetween('gym_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
        ->orderBy('raid_expire', 'asc')
        ->simplePaginate(15);

      return $gatherRaids;
    }
}
