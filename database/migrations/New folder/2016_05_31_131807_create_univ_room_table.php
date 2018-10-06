<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_room', function (Blueprint $table) {
            $table->uuid('room_tran_code')->primary();
            $table->string('room_code');
            $table->string('room_title');
            $table->string('room_slug');
            $table->string('room_type');
            $table->string('room_capacity');
            $table->string('room_facilities');
            $table->string('building_tran_code');
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
        Schema::drop('univ_room');
    }
}
