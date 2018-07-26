<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
      DB::table('group')->insert([
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone"]',
          'count' => 10,
          'admin' => 'domezone',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone2"]',
          'count' => 10,
          'admin' => 'domezone2',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone3"]',
          'count' => 10,
          'admin' => 'domezone3',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone4"]',
          'count' => 10,
          'admin' => 'domezone4',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone5"]',
          'count' => 10,
          'admin' => 'domezone5',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone6"]',
          'count' => 10,
          'admin' => 'domezone6',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone7"]',
          'count' => 10,
          'admin' => 'domezone7',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone8"]',
          'count' => 10,
          'admin' => 'domezone8',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone9"]',
          'count' => 10,
          'admin' => 'domezone9',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone10"]',
          'count' => 10,
          'admin' => 'domezone10',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
        [
          'type' => 1,
          'subType1' => 1,
          'description' => str_random(100),
          'users' => '["domezone","domezone5","domezone10"]',
          'count' => 10,
          'admin' => 'domezone10',
          'status' => 'queued',
          'address' => '15567 e ford circle',
          'location' => 'Aurora, CO',
          'lat' => 39.7397,
          'lon' => -104.794,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
          'timezone' => 'America/Denver'
        ],
      ]);
    }
}
