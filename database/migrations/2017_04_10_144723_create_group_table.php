<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->integer('subType1')->nullable();
            $table->integer('subType2')->nullable();
            $table->string('description', 255);
            $table->text('users');
            $table->integer('count');
            $table->string('admin');
            $table->string('status');
            $table->string('address');
            $table->string('location');
            $table->double('lat');
            $table->double('lon');
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
        Schema::dropIfExists('group');
    }
}
