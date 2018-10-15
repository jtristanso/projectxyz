<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class TopicCommentReply extends APIModel
{
    protected $table	 	= 'topic_comment_replies';
    protected $fillable = ['topic_comment_id', 'account_id', 'text'];
    protected $dates = ['created_at', 'updated_at'];
    protected $dateFormat = 'Y-m-d H:i:s';
    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromFormat('Y-m-d H:i:s', $date)->copy()->tz('Asia/Manila')->format('F j, Y g:i A');
    }
}
