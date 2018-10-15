<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussionReport extends APIModel
{
    protected $table = 'discussion_reports';
    protected $fillable = ['account_id', 'discussion_post_id', 'text'];
}
