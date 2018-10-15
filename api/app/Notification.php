<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Notification extends APIModel
{
    protected $table = 'notifications';
    protected $fillable = ['creator', 'account_id', 'course_id', 'title', 'description', 'payload', 'url', 'parameter', 'status'];
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->copy()->tz('Asia/Manila')->format('F j, Y g:i A');
    }
}
