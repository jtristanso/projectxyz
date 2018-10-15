<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventComment extends APIModel
{
    protected $table = 'event_comments';
    protected $fillable = ['account_id', 'event_id', 'text'];
}
