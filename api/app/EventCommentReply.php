<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventCommentReply extends APIModel
{
    protected $table = 'event_comment_replies';
    protected $fillable = ['account_id', 'event_comment_id', 'text'];
}
