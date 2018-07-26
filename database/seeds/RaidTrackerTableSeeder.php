<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RaidTrackerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 100; $i++)
        {
          DB::table('raidtracker')->insert([
            [
              'pokemon_name' => 'Tyrantar',
              'pokemon_id' => $i * 2,
              'address' => "15567 E Ford Circle",
              'location' => 'Aurora, CO',
              'star_level' => 4,
              'gym_lat' => 39.698250,
              'gym_lon' => -104.807020,
              'raid_expire' => Carbon::now()->addHour()->format('Y-m-d H:i:s'),
            ]
          ]);
        }
    }
}
