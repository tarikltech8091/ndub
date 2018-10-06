<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivSemesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_semester', function (Blueprint $table) {
            $table->uuid('semester_tran_code')->primary();
            $table->string('semester_code');
            $table->string('semester_title');
            $table->string('semester_slug');
            $table->integer('semester_sequence');
            $table->string('semester_duration');
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
        Schema::drop('univ_semester');
    }
}
