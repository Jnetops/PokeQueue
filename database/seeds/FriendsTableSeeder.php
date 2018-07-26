<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('friends')->insert([
          [
            'request_uid' => 'domezone',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'request_uid' => 'domezone3',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'request_uid' => 'domezone4',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'request_uid' => 'domezone5',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'request_uid' => 'domezone6',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'request_uid' => 'domezone7',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
          [
            'request_uid' => 'domezone8',
            'received_uid' => 'domezone2',
            'status' => 'accepted',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
          ],
        ]);
    }
}
