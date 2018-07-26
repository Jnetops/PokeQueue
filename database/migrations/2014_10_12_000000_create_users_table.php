<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('trainer_name', 15)->unique();
            $table->integer('trainer_level');
            $table->integer('trainer_team');
            $table->integer('age')->nullable();
            $table->integer('sex')->nullable();
            $table->string('location');
            $table->string('timezone');
            $table->double('lat');
            $table->double('lon');
            $table->string('trainer_updated')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
