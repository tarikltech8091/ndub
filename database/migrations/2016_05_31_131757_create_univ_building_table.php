<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivBuildingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_building', function (Blueprint $table) {
            $table->uuid('building_tran_code')->primary();
            $table->string('building_code');
            $table->string('building_title');
            $table->string('building_slug');
            $table->string('campus_tran_code');
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
        Schema::drop('univ_building');
    }
}
