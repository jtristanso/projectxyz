<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWorkExperiencesTableChangeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_work_experiences', function (Blueprint $table) {
            $table->string('month_started')->nullable()->change();
            $table->string('month_ended')->nullable()->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_work_experiences', function (Blueprint $table) {
            //
        });
    }
}
