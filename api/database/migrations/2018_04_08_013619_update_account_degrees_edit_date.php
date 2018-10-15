<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountDegreesEditDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_degrees', function (Blueprint $table) {
            $table->unsignedInteger('year_started')->nullable()->change();
            $table->unsignedInteger('year_end')->nullable()->change();
            $table->string('month_started')->after('year_started')->nullable();
            $table->string('month_end')->after('year_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_degrees', function (Blueprint $table) {
            //
        });
    }
}
