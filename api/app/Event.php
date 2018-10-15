<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends APIModel
{
    protected $table = 'events';
    protected $fillable = ['status', 'account_id', 'organization_id', 'title', 'more_info', 'date', 'start_time', 'end_time', 'venue', 'ticket_price', 'max_attendees'];
}
