<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussionPost extends APIModel
{
    protected $table = 'discussion_posts';
    protected $fillable = ['account_id', 'text', 'tags', 'status'];
}
