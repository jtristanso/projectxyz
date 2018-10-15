<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('quizes_rate')->nullable();
            $table->unsignedInteger('exams_rate')->nullable();
            $table->unsignedInteger('attendance_rate')->nullable();
            $table->unsignedInteger('passing_percentage_quizes')->nullable();
            $table->unsignedInteger('passing_percentage_exams')->nullable();
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
        Schema::dropIfExists('grade_settings');
    }
}
