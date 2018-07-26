<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaidtrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raidtracker', function (Blueprint $table) {
            $table->increments('id');
            $table->double('gym_lat');
            $table->double('gym_lon');
            $table->string('pokemon_name');
            $table->integer('pokemon_id');
            $table->string('address');
            $table->string('location');
            $table->integer('star_level');
            $table->timestamp('raid_expire');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raidtracker');
    }
}
