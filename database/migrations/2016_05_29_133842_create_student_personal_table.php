<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentPersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_personal', function (Blueprint $table) {
            $table->uuid('student_personal_tran_code');
            $table->string('student_tran_code');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('blood_group');
            $table->string('place_of_birth');
            $table->string('marital_status');
            $table->string('nationality');
            $table->string('email');
            $table->string('phone');
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
        Schema::drop('student_personal');
    }
}
