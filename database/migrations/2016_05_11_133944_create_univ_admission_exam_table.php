<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivAdmissionExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_admission_exam', function (Blueprint $table) {
            $table->uuid('admission_tran_code');
            $table->timestamp('admission_campaign_start_date');
            $table->timestamp('admission_campaign_end_date');
            $table->timestamp('admission_exam_date');
            $table->string('admission_exam_status');
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
        Schema::drop('univ_admission_exam');
    }
}
