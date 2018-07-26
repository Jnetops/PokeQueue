<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            // types:
            // 1 - gym battle - sub options: 1-2-3, represet teams, 1 blue, 2 red, 3 yellow
            // 2 - poke hunting - sub options: 1 = event farming, 2 = nest farming, 3 = anything
            // sub option 2, only present on type 2 sub option 2: 1-251 for pokemon list
            //3 - item farming - no sub options
            $table->increments('id');
            $table->string('admin');
            $table->text('users');
            $table->string('description', 255);
            $table->timestamp('datetime');
            $table->integer('type');
            $table->integer('subType1')->nullable();
            $table->integer('subType2')->nullable();
            $table->boolean('privacy');
            $table->integer('privacySubOption')->nullable();
            $table->string('address');
            $table->string('location');
            $table->double('lat', 8, 5);
            $table->double('lon', 8, 5);
            $table->string('timezone');
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
        Schema::dropIfExists('events');
    }
}
