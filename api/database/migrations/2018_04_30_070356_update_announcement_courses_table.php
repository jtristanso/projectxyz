<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAnnouncementCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcement_courses', function (Blueprint $table) {
            $table->unsignedInteger('announcement_id')->after('id');
            $table->unsignedInteger('course_id')->after('announcement_id');
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
        Schema::table('announcement_courses', function (Blueprint $table) {
            //
        });
    }
}
