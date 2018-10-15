<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExamsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['type', 'start', 'end', 'timer']);
            $table->date('available_date')->after('description')->nullable();
            $table->time('available_time')->after('available_date')->nullable();
            $table->float('time_limit', 4, 2)->after('available_time')->nullable();
            $table->tinyInteger('timer_flag')->after('time_limit')->default(1);
            $table->unsignedInteger('time_per_question')->after('timer_flag')->default(null)->nullable();
            $table->string('orders_setting')->after('time_per_question')->default('SHUFFLE');
            $table->string('choices_setting')->after('orders_setting')->default('SHUFFLE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            //
        });
    }
}
