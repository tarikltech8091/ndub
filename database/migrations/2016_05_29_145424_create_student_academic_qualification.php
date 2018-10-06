<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAcademicQualification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_academic_qualification', function (Blueprint $table) {
            $table->uuid('student_qualification_tran_code');
            $table->string('student_tran_code'); 
            $table->string('exam_type');
            $table->string('exam_group');
            $table->string('exam_board');
            $table->string('result_type');
            $table->string('exam_roll_number');
            $table->string('passing_year');
            $table->string('result_gpa');
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
         Schema::drop('student_academic_qualification');
    }
}
