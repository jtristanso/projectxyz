<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessengerGroup extends APIModel
{
    protected $table = 'messenger_groups';
    protected $fillable = ['account_id', 'title'];
}
