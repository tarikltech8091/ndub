<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantPersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('applicant_personal', function (Blueprint $table) {
            $table->uuid('applicant_personal_tran_code');
            $table->string('applicant_tran_code');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('blood_group');
            $table->string('place_of_birth');
            $table->string('marital_status');
            $table->string('nationality');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile');
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
        Schema::drop('applicant_personal');
    }
}
