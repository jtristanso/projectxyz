<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Test extends APIModel
{
    protected $table = 'tests';
    protected $fillable = ['course_id', 'type', 'description', 'available_date', 'available_time', 'time_per_question', 'orders_setting', 'choices_setting', 'code'];
    // protected $dates = ['available_date', 'available_time'];
    // public function getAvailableTimeAttribute($date)
    // {
    //   return Carbon::createFromFormat('H:i:s', $date)->copy()->tz('Asia/Manila')->format('g:i A');
    // }
    // public function getAvailableDateAttribute($date)
    // {
    //   return Carbon::createFromFormat('Y-m-d', $date)->copy()->tz('Asia/Manila')->format('F j, Y');
    // }
}
