<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussionReaction extends APIModel
{
    protected $table = 'discussion_reactions';
    protected $fillable = ['account_id', 'discussion_post_id'];
}
