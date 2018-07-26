<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group-queue', function (Blueprint $table) {
            $table->increments('queue_id');
            $table->integer('userid')->unique();
            $table->text('type');
            $table->double('lat');
            $table->double('lon');
            $table->integer('distance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group-queue');
    }
}
