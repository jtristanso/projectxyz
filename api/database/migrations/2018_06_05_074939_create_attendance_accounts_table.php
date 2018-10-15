<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attendance_id');
            $table->unsignedInteger('account_id');
            $table->longText('remarks')->nullable();
            $table->string('status')->default('STATUS');
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
        Schema::dropIfExists('attendance_accounts');
    }
}
