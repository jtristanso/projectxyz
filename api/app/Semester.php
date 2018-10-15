<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Semester extends APIModel
{
    protected $table = "semesters";
    protected $fillable = ['school_id', 'description',  'start_date', 'end_date'];
    protected $datas = ['start_date', 'end_date'];
    public function getStartDateAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d', $date)->copy()->tz('Asia/Manila')->format('F j, Y');
    }
    public function getEndDateAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d', $date)->copy()->tz('Asia/Manila')->format('F j, Y');
    }
}
