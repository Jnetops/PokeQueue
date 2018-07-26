<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoketrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poketracker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pokemon_name');
            $table->integer('pokemon_id');
            $table->string('pokemon_rarity');
            $table->integer('pokemon_cp')->nullable();
            $table->integer('pokemon_iv')->nullable();
            $table->string('pokemon_move_1')->nullable();
            $table->string('pokemon_move_2')->nullable();
            $table->double('pokemon_lat');
            $table->double('pokemon_lon');
            $table->timestamp('pokemon_expire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poketracker');
    }
}
