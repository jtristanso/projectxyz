<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGradeSettingsTableUpdateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_settings', function (Blueprint $table) {
            // $table->renameColumn('passing_percentage_quizes', 'passing_percentage_quizzes');
            // $table->renameColumn('quizes_rate', 'quizzes_rate');
            $table->unsignedInteger('projects_rate')->after('attendance_rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_settings', function (Blueprint $table) {
            //
        });
    }
}
