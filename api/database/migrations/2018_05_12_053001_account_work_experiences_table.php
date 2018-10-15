<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountWorkExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_work_experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->string('position')->nullable();
            $table->string('company')->nullable();
            $table->string('company_address')->nullable();
            $table->unsignedInteger('year_started')->nullable();
            $table->unsignedInteger('month_started')->nullable();
            $table->unsignedInteger('year_ended')->nullable();  
            $table->unsignedInteger('month_ended')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_work_experiences');
    }
}
