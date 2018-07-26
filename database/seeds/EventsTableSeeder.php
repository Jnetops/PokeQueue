<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('events')->insert([
        [
          'admin' => 'domezone1',
          'users' => '["domezone1"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now(),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone2',
          'users' => '["domezone2"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 2,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now(),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone3',
          'users' => '["domezone3"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 3,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(1),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone4',
          'users' => '["domezone4"]',
          'description' => str_random(100),
          'type' => 2,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(1),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone5',
          'users' => '["domezone5"]',
          'description' => str_random(100),
          'type' => 2,
          'subType1' => 2,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(2),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone6',
          'users' => '["domezone6"]',
          'description' => str_random(100),
          'type' => 2,
          'subType1' => 3,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(1),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone7',
          'users' => '["domezone7"]',
          'description' => str_random(100),
          'type' => 3,
          'subType1' => 0,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(2),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone8',
          'users' => '["domezone8"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(3),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone9',
          'users' => '["domezone9"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(3),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone10',
          'users' => '["domezone10"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(4),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone1',
          'users' => '["domezone1"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(5),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone2',
          'users' => '["domezone2"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(5),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone3',
          'users' => '["domezone3"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(5),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone4',
          'users' => '["domezone4"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(5),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone5',
          'users' => '["domezone5"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(6),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
        [
          'admin' => 'domezone6',
          'users' => '["domezone6"]',
          'description' => str_random(100),
          'type' => 1,
          'subType1' => 1,
          'privacy' => 0,
          'address' => '1520 E Colfax Ave',
          'location' => 'Denver, CO',
          'datetime' => Carbon::now()->addDays(6),
          'lat' => 39.7397,
          'lon' => -104.794,
          'timezone' => 'America/Denver'
        ],
      ]);
    }
}
