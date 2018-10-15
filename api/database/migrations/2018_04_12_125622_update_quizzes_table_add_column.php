<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuizzesTableAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['start', 'end', 'timer']);
            $table->date('available')->after('type');
            $table->time('time_limit')->after('available');
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
        Schema::table('quizzes', function (Blueprint $table) {
            //
        });
    }
}
