<?php

namespace App\http\models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Traits\Distance;
use App\User;
use Carbon\Carbon;
use File;
use App\Http\Models\Friends;
use App\Traits\SendNotifications;

class PokeTracker extends Model
{
    use SendNotifications;
    use Distance;
    protected $fillable = ['pokemon_name', 'pokemon_rarity', 'pokemon_id', 'pokemon_cp', 'pokemon_iv', 'pokemon_move_1', 'pokemon_move_2', 'pokemon_lat', 'pokemon_lon', 'pokemon_expire'];
    protected $table = 'poketracker';

    public function submitPokemon($request) {


      if ($request->has('pokemon_move_1') && $request->has('pokemon_move_2'))
      {
        $path = public_path() . '/protos/pokemon.json';// ie: /var/www/laravel/app/storage/json/filename.json
        if (!File::exists($path)) {
            return array('Success' => 'False', 'Error' => 'Failed to find move list');
        }

        $file = File::get($path); // string
        $file = json_decode($file, true);

        foreach ($file as $poke)
        {
          if ($poke['Number'] == $request->input('pokemon_id'))
          {
            if (!in_array($request->input('pokemon_move_1'), $poke['Fast Attack(s)']))
            {
              return array('Success' => 'False', 'Error' => 'Invalid fast moveset');
            }
            elseif (!in_array($request->input('pokemon_move_2'), $poke['Special Attack(s)']))
            {
                return array('Success' => 'False', 'Error' => 'Invalid charge moveset');
            }
          }
        }
      }
      elseif ($request->has('pokemon_move_1') && !$request->has('pokemon_move_2'))
      {
        return array('Success' => 'False', 'Error' => 'Only 1 moveset provided');
      }
      elseif (!$request->has('pokemon_move_1') && $request->has('pokemon_move_2'))
      {
        return array('Success' => 'False', 'Error' => 'Only 1 moveset provided');
      }

      $checkExisting = PokeTracker::where('pokemon_lat', $request->input('pokemon_lat'))
                        ->where('pokemon_lon', $request->input('pokemon_lon'))
                        ->where('pokemon_id', $request->input('pokemon_id'))
                        ->where('pokemon_expire', '>=', Carbon::now())
                        ->first();

      if ($checkExisting)
      {
        // possibly update moveset / cp / iv, one already exists
        return array('Success' => 'False', 'Error' => 'Pokemon already exists');
      }
      else {

        if ($request->has('expire_hour'))
        {
          if ($request->has('expire_minute'))
          {
            if ($request->input('expire_hour') <= 1)
            {
              $expireTime = Carbon::now()->addHours($request->input('expire_hour'))->addMinutes($request->input('expire_minute'));
            }
            else {
              $expireTime = Carbon::now()->addHours($request->input('expire_hour'));
            }
          }
          else {
            $expireTime = Carbon::now()->addHours($request->input('expire_hour'));
          }
        }
        elseif ($request->has('expire_minute'))
        {
          $expireTime = Carbon::now()->addMinutes($request->input('expire_minute'));
        }
        else {
          $expireTime = Carbon::now()->addHours(1);
        }

        $request->request->add(['pokemon_expire' => $expireTime]);

        $path = public_path() . '/protos/rarity.json';
        if (!File::exists($path)) {
            return array('Success' => 'False', 'Error' => 'Failed to gather pokemon information');
        }

        $file = File::get($path); // string
        $file = json_decode($file, true);

        foreach ($file as $key => $pokemon)
        {
          if ($key == $request->input('pokemon_id'))
          {
            $pokemonName = $pokemon['name'];
            $pokemonRarity = $pokemon['rarity'];
          }
        }

        $request->request->add(['pokemon_name' => $pokemonName, 'pokemon_rarity' => $pokemonRarity]);

        $addPokemon = PokeTracker::create($request->all());

        if ($addPokemon)
        {
          $regetPokemon = PokeTracker::where('id', $addPokemon->id)->first();
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

          $this->pokeTrackerAdd($users, $regetPokemon);

          return array('Success' => 'True', 'pokemons' => $addPokemon);
        }
        else {
          return array('Success' => 'False', 'Error' => 'Failed to add pokemon to tracker');
        }
      }

    }

    public function gatherPokemon($request) {

      if ($request->has('distance'))
      {
        $userid = Auth::user()->id;
        $userLoc = User::where('id', $userid)->first(['lat', 'lon']);

        $distanceArray = $this->gatherHighLow($userLoc->lat, $userLoc->lon, $request->input('distance'));
        if (array_key_exists('error', $distanceArray))
        {
          return array('Success' => 'False', 'Error' => $distanceArray['error']);
        }
        else {
          if ($request->has('filter-iv'))
          {
            if($request->has('filter-rarity'))
            {
              $gatherPokes = PokeTracker::whereBetween('pokemon_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                      ->whereBetween('pokemon_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                      ->where('pokemon_expire', '>=', Carbon::now())
                                      ->where('pokemon_rarity', $request->input('filter-rarity'))
                                      ->whereBetween('pokemon_iv', [$request->input('filter-iv'), $request->input('filter-iv') + 9])
                                      ->simplePaginate(15);
            }
            else {
              $gatherPokes = PokeTracker::whereBetween('pokemon_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                      ->whereBetween('pokemon_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                      ->where('pokemon_expire', '>=', Carbon::now())
                                      ->whereBetween('pokemon_iv', [$request->input('filter-iv'), $request->input('filter-iv') + 9])
                                      ->simplePaginate(15);
            }
          }
          elseif ($request->has('filter-rarity'))
          {
             $gatherPokes = PokeTracker::whereBetween('pokemon_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                      ->whereBetween('pokemon_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                      ->where('pokemon_expire', '>=', Carbon::now())
                                      ->where('pokemon_rarity', $request->input('filter-rarity'))
                                      ->simplePaginate(15);
          }
          else {
            $gatherPokes = PokeTracker::whereBetween('pokemon_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                      ->whereBetween('pokemon_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                      ->where('pokemon_expire', '>=', Carbon::now())
                                      ->simplePaginate(15);
          }

          return array('Success' => 'True', 'Pokemons' => $gatherPokes);
        }
      }
      else {
        if ($request->has('filter-id')) {
          $gatherPokes = PokeTracker::where('pokemon_expire', '>=', Carbon::now())
                                    ->where('id', $request->input('filter-id'))
                                    ->simplePaginate(1);
          if ($gatherPokes->count())
          {
              return array('Success' => 'True', 'Pokemons' => $gatherPokes, 'Filter' => 'True');
          }
          else {
            $maxID = PokeTracker::find(\DB::table('poketracker')->max('id'));
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
        return array('Success' => 'False', 'Error' => 'No distance supplied');
      }

    }

    public function Gather_Home_Poke($distanceArray) {

      $gatherPokes = PokeTracker::whereBetween('pokemon_lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                ->whereBetween('pokemon_lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                ->where('pokemon_expire', '>=', Carbon::now())
                                ->simplePaginate(15);
      return $gatherPokes;

    }
}
