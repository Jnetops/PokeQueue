<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_chat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('trainer_name', 15);
            $table->string('comment', 255);
            $table->string('location')->nullable();
            $table->double('lat', 8, 5)->nullable();
            $table->double('lon', 8, 5)->nullable();
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
        Schema::dropIfExists('general_chat');
    }
}
