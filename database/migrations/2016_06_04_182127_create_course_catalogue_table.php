<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseCatalogueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_catalogue', function (Blueprint $table) {
            $table->uuid('course_catalogue_tran_code')->primary();
            $table->string('course_catalogue_slug')->unique();
            $table->string('course_catalogue_program');
            $table->string('course_category_slug');
            $table->integer('no_of_courses');
            $table->string('total_credit_hours');
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
        Schema::drop('course_catalogue');
    }
}
