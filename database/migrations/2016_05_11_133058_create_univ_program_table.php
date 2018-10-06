<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_program', function (Blueprint $table) {
            $table->uuid('program_tran_code')->primary();
            $table->string('program_id');
            $table->string('program_code');
            $table->string('program_title');
            $table->string('program_slug');
            $table->string('program_head');
            $table->string('department_tran_code');
            $table->string('degree_tran_code');
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
        Schema::drop('univ_program');
    }
}
