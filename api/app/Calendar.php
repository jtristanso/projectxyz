<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends APIModel
{
    protected $table = 'calendars';
    protected $fillable = ['course_id', 'account_id', 'title', 'remarks', 'date'];
}
