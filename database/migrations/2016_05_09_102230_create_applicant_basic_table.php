<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_basic', function (Blueprint $table) {
            $table->uuid('applicant_tran_code');
            $table->string('applicant_serial_no');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('program');
            $table->string('semester');
            $table->string('academic_year');
            $table->string('app_image_url');
            $table->string('applicant_fees_amount');
            $table->string('payment_by');
            $table->string('payment_slip_no')->unique();
            $table->string('payment_bank_name');
            $table->string('applicant_eligiblity');
            $table->string('payment_status');
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
         Schema::drop('applicant_basic');
    }
}
