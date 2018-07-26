<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\RaidTracker;
use App\Traits\ValidateInput;
use View;
use File;

class RaidTrackerController extends Controller
{
    use ValidateInput;
    public function AddRaid(Request $request)
    {
        $validateInput = $this->raidTrackerAddValidate($request);
        if ($validateInput->passes())
        {
            $raid = new RaidTracker();
            $addRaid = $raid->Add_Raid($request);
            if ($addRaid['Success'] == 'False')
            {
                return redirect()->route('raidTrackerAdd')->with('error', $addRaid['Error']);
            }
            else {
                // return to raid view, figure out how to do this.
                return redirect()->route('raidTrackerAll', ['distance' => 25]);
            }
        }
        else {
          return redirect()->route('raidTrackerAdd')->with('errors', $validateInput->errors());
        }
    }

    public function GetRaids(Request $request)
    {
        $validateInput = $this->raidTrackerGetValidate($request);
        if ($validateInput->passes())
        {
            $raid = new RaidTracker();
            $gather = $raid->Get_Raids($request);

            $path = public_path() . '/protos/raids.json';// ie: /var/www/laravel/app/storage/json/filename.json
            if (!File::exists($path)) {
    	        return array('Success' => 'False', 'Error' => 'Failed to find raid list');
            }

            $file = File::get($path); // string
            $file = json_decode($file, true);

            return View::make('raidtracker_all',['raids' => $gather, 'raidPokemon' => $file]);
        }
        else {
          echo 'Validation Failed: ' . $validateInput->errors();
        }
    }

    public function GetSpecificRaid(Request $request, $id)
    {
        $validateInput = $this->raidTrackerGetValidate($request);
        if ($validateInput->passes())
        {
            if ($request->has('filter-id'))
            {
                $request->merge(['filter-id' => $id]);
            }
            else {
                $request->request->add(['filter-id' => $id]);
            }
            $raid = new RaidTracker();
            $gather = $raid->Get_Raids($request);

            $path = public_path() . '/protos/raids.json';// ie: /var/www/laravel/app/storage/json/filename.json
            if (!File::exists($path)) {
    	        return array('Success' => 'False', 'Error' => 'Failed to find raid list');
            }

            $file = File::get($path); // string
            $file = json_decode($file, true);

            if (array_key_exists('Expired', $gather))
            {
              return View::make('expired', ['Type' => 'Raid']);
            }
            if (array_key_exists('Found', $gather))
            {
              return View::make('notfound', ['Type' => 'Raid']);
            }

            return View::make('raidtracker_all',['raids' => $gather, 'raidPokemon' => $file]);
        }
        else {
          echo 'Validation Failed: ' . $validateInput->errors();
        }
    }

    public function AddRaidForm(Request $request)
    {
        $path = public_path() . '/protos/raids.json';// ie: /var/www/laravel/app/storage/json/filename.json
        if (!File::exists($path)) {
	        return array('Success' => 'False', 'Error' => 'Failed to find raid list');
        }

        $file = File::get($path); // string
        $file = json_decode($file, true);

        if ($request->has('errors'))
        {
          return View::make('raidtracker_add',['errors' => $request->input('errors'), 'raids' => $file]);
        }

        return View::make('raidtracker_add',['raids' => $file]);
    }
}
