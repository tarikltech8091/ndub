<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGurdiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_gurdians', function (Blueprint $table) {
            $table->uuid('student_gurdians_tran_code');
            $table->string('student_tran_code');
            $table->string('relation');
            $table->string('gurdian_name');
            $table->string('occupation');
            $table->string('mobile');
            $table->string('email');
            $table->string('emergency_contact');
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
        Schema::drop('student_gurdians');
    }
}
