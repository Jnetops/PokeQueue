<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    
    public function run()
    {
      DB::table('users')->insert([
        [
          'username' => 'domezone',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone2',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone2',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone3',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone3',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone4',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone4',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone5',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone5',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone6',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone6',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone7',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone7',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone8',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone8',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone9',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone9',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
        [
          'username' => 'domezone10',
          'email' => str_random(10).'@gmail.com',
          'password' => bcrypt('secret'),
          'trainer_name' => 'domezone10',
          'trainer_team' => 3,
          'trainer_level' => 30,
          'location' => 'Denver, CO',
          'timezone' => 'America/Denver',
          'lat' => 39.7397,
          'lon' => -104.794,
          'trainer_updated' => '2017-04-27'
        ],
      ]);
    }
}
