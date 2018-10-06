<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllAccountsFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_accounts_fees', function (Blueprint $table) {
            $table->uuid('accounts_fees_tran_code');
            $table->string('fees_type_name');
            $table->string('fees_amount');
            $table->string('fees_related_program_id');
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
        Schema::drop('univ_program');
    }
}
