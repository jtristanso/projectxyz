<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class DiscussionAnswer extends APIModel
{
    protected $table = 'discussion_answers';
    protected $fillable = ['discussion_post_id', 'account_id', 'text', 'best_solution'];
    protected $dates = ['created_at', 'updated_at'];
    protected $dateFormat = 'Y-m-d H:i:s';
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->copy()->tz('Asia/Manila')->format('F j, Y g:i A');
    }
}
