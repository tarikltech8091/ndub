<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_basic', function (Blueprint $table) {
            $table->uuid('student_tran_code');
            $table->string('applicant_tran_code');
            $table->string('student_serial_no');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('program');
            $table->string('semester');
            $table->string('academic_year');
            $table->string('student_image_url');
            $table->string('mobile');
            $table->string('student_status');
            $table->timestamp('admission_date');
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
        Schema::drop('student_basic');
    }
}
