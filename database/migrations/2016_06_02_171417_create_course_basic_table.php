<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_basic', function (Blueprint $table) {
            $table->uuid('course_basic_tran_code');
            $table->string('course_slug')->unique();
            $table->string('course_code');
            $table->string('course_title');
            $table->string('course_type');
            $table->integer('level');
            $table->integer('term');
            $table->string('course_program');
            $table->string('course_category');
            $table->string('credit_hours');
            $table->string('per_credit_fees_amount');
            $table->string('total_credit_fees_amount');
            $table->string('course_description');
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
         Schema::drop('course_basic');
    }
}
