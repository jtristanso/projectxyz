<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicCommentStar extends APIModel
{
    protected $table = 'topic_comment_stars';
    protected $fillable = ['topic_id', 'account_id'];
}
