<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantAcademicResultDeatilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_academic_result_detail', function (Blueprint $table) {
            $table->uuid('applicant_academic_result_tran_code');
            $table->string('applicant_academic_tran_code');
            $table->string('exam_name');
            $table->longText('academic_detail');
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
        Schema::drop('applicant_academic_result_deatil');
    }
}
