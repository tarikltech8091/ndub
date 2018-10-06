<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantGurdiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_gurdians', function (Blueprint $table) {
            $table->uuid('applicant_gurdians_tran_code');
            $table->string('applicant_tran_code');
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
        Schema::drop('applicant_gurdians');
    }
}
