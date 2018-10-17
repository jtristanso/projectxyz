<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends APIModel
{
    protected $table = 'comments';
    protected $fillable = ['account_id', 'event_id', 'text'];
}
