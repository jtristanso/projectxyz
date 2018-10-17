<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends APIModel
{
    protected $table = 'comment_replies';
    protected $fillable = ['account_id', 'event_comment_id', 'text'];
}
