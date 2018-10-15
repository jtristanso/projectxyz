<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatWorkExperiencesTableAddCurrentFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_work_experiences', function (Blueprint $table) {
            $table->boolean('current_flag')->after('month_started');
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
