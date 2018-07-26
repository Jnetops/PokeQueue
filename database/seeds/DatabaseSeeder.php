<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(FriendsTableSeeder::class);
        $this->call(RaidTrackerTableSeeder::class);
        $this->call(PokeTrackerTableSeeder::class);
    }
}
