<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PokeTrackerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 100; $i++)
        {
            DB::table('poketracker')->insert([
            [
              'pokemon_name' => 'Tyrantar',
              'pokemon_id' => $i * 2,
              'pokemon_rarity' => 'Ultra Rare',
              'pokemon_lat' => 39.698250,
              'pokemon_lon' => -104.807020,
              'pokemon_expire' => Carbon::now()->addHour()->format('Y-m-d H:i:s'),
            ]
          ]);
        }
    }
}
