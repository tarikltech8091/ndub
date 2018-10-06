<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnivDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univ_department', function (Blueprint $table) {
            $table->uuid('department_tran_code')->primary();
            $table->string('department_code');
            $table->string('department_title');
            $table->string('department_slug');
            $table->string('department_dean_chairperson');
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
        Schema::drop('univ_department');
    }
}
