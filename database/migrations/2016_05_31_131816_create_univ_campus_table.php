<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivCampusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_campus', function (Blueprint $table) {
            $table->uuid('campus_tran_code')->primary();
            $table->string('campus_code');
            $table->string('campus_title');
            $table->string('campus_slug');
            $table->string('campus_location');
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
        Schema::drop('univ_campus');
    }
}
